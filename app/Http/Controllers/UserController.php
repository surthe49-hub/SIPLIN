<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.manage', only: ['index', 'show']),
            new Middleware('permission:users.create', only: ['store']),
            new Middleware('permission:users.edit', only: ['update', 'resetPassword']),
            new Middleware('permission:users.delete', only: ['destroy']),
        ];
    }

    /**
     * Tampilkan daftar pengguna.
     */
    public function index(Request $request): View
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $query = User::query();

        if (auth()->user()->role === 'user') {
            $query->where('id', auth()->id());
        } elseif (auth()->user()->role === 'staff') {
            $query->where('role', 'user');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (auth()->user()->role === 'admin' && $request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $perPage = min($request->get('per_page', 15), 100);
        $users = $query->orderBy('name')->paginate($perPage)->withQueryString();

        if (auth()->user()->role === 'admin') {
            $roles = [
                'admin' => 'Administrator',
                'staff' => 'Staff',
                'user' => 'User'
            ];
            $adminCount = User::where('role', 'admin')->count();
            $canAddAdmin = $adminCount < 3;
        } elseif (auth()->user()->role === 'staff') {
            $roles = [
                'user' => 'User'
            ];
            $adminCount = 0;
            $canAddAdmin = false;
        } else {
            $roles = [];
            $adminCount = 0;
            $canAddAdmin = false;
        }

        return view('users.index', compact('users', 'roles', 'adminCount', 'canAddAdmin'));
    }

    /**
     * Simpan pengguna baru.
     *
     * Handle 3 scenario:
     * 1. Email belum pernah dipakai → CREATE user baru
     * 2. Email dipakai user AKTIF → validation error (email sudah terdaftar)
     * 3. Email dipakai user SOFT-DELETED → RESTORE + update datanya
     *    (biar admin tidak bingung kenapa email "sudah terdaftar" padahal
     *    tidak muncul di list)
     */
    public function store(Request $request)
    {
        // Validation: unique hanya cek user AKTIF (WHERE deleted_at IS NULL)
        // User soft-deleted tidak trigger conflict — akan di-handle di logic bawah
        $emailUniqueRule = Rule::unique('users', 'email')->whereNull('deleted_at');

        if (auth()->user()->role === 'staff') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', $emailUniqueRule],
                'password' => ['required', 'confirmed', Password::defaults()],
                'phone' => ['nullable', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'max:20'],
                'role' => ['required', 'in:user'],
                'is_active' => ['boolean'],
            ]);
        } elseif (auth()->user()->role === 'admin') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', $emailUniqueRule],
                'password' => ['required', 'confirmed', Password::defaults()],
                'phone' => ['nullable', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'max:20'],
                'role' => ['required', 'in:admin,staff,user'],
                'is_active' => ['boolean'],
            ]);
        } else {
            abort(403, 'Anda tidak memiliki izin untuk membuat pengguna.');
        }

        if (auth()->user()->role === 'admin' && $validated['role'] === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount >= 3) {
                $errorMsg = 'Jumlah Admin sudah mencapai batas maksimal (3 orang).';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return back()->withErrors(['role' => $errorMsg])->withInput();
            }
        }

        $email = strtolower(trim($validated['email']));

        $userData = [
            'name' => trim($validated['name']),
            'email' => $email,
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        try {
            // Cek dulu apakah ada user soft-deleted dengan email ini
            $existingTrashed = User::onlyTrashed()->where('email', $email)->first();

            if ($existingTrashed) {
                // Restore + update datanya
                $existingTrashed->restore();
                $existingTrashed->fill($userData);
                $existingTrashed->role = $validated['role']; // role di luar fillable (defense-in-depth)
                $existingTrashed->save();

                $successMsg = "Pengguna berhasil dipulihkan dari data terhapus dan diperbarui.";

                ActivityLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'restore_user',
                    'description' => "Admin memulihkan user soft-deleted: {$email}",
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                $user = $existingTrashed;
            } else {
                // Create user baru
                $user = new User($userData);
                $user->role = $validated['role']; // role di luar fillable (defense-in-depth)
                $user->save();

                $successMsg = "Pengguna berhasil ditambahkan.";
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMsg,
                ]);
            }

            return redirect()->route('users.index')->with('success', $successMsg);
        } catch (\Exception $e) {
            Log::error('User creation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_data' => array_merge($userData, ['password' => '[HIDDEN]'])
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal membuat pengguna.'], 500);
            }

            return back()->with('error', 'Gagal membuat pengguna.')->withInput();
        }
    }

    /**
     * Tampilkan detail pengguna.
     */
    public function show(User $user): View
    {
        $activities = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get();

        return view('users.show', compact('user', 'activities'));
    }

    /**
     * Update pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->whereNull('deleted_at')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'max:20'],
            'role' => ['required', 'in:admin,staff,user'],
            'is_active' => ['boolean'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
        ]);

        $currentRole = $user->role;
        $newRole = $validated['role'];
        $isUpgradeToAdmin = $newRole === 'admin' && $currentRole !== 'admin';

        if ($isUpgradeToAdmin) {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount >= 3) {
                $errorMsg = 'Jumlah Admin sudah mencapai batas maksimal (3 orang).';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return back()->withErrors(['role' => $errorMsg])->withInput();
            }
        }

        $userData = [
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ? trim($validated['phone']) : null,
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        try {
            if ($request->hasFile('avatar')) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
            }

            $user->fill($userData);
            $user->role = $validated['role']; // role di luar fillable (defense-in-depth)
            $user->save();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Pengguna berhasil diperbarui.']);
            }

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('User update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui pengguna.'], 500);
            }

            return back()->with('error', 'Gagal memperbarui pengguna.')->withInput();
        }
    }

    /**
     * Reset password pengguna oleh admin.
     * Admin bisa kirim password custom (dari modal), atau backend generate kalau tidak ada.
     */
    public function resetPassword(Request $request, User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mereset password pengguna.');
        }

        $newPassword = $request->input('password');

        if ($newPassword) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'max:50'],
            ], [
                'password.min' => 'Password minimal 8 karakter.',
                'password.max' => 'Password maksimal 50 karakter.',
            ]);
        } else {
            $newPassword = Str::random(10);
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'reset_password',
            'description' => "Admin mereset password untuk user: {$user->email}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'email' => $user->email,
                'new_password' => $newPassword,
            ]);
        }

        return redirect()->route('users.index')
            ->with('reset_password_result', [
                'email' => $user->email,
                'new_password' => $newPassword,
            ])
            ->with('success', "Password untuk {$user->name} berhasil direset.");
    }

    /**
     * Soft delete pengguna (dapat dikembalikan).
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus dan dapat dikembalikan jika diperlukan.');
    }
}