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
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.manage', only: ['index', 'show']),
            new Middleware('permission:users.create', only: ['create', 'store']),
            new Middleware('permission:users.edit', only: ['edit', 'update', 'resetPassword']),
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
     * Tampilkan form tambah pengguna.
     */
    public function create(): View
    {
        if (auth()->user()->role === 'admin') {
            $roles = [
                'admin' => 'Administrator',
                'staff' => 'Staff',
                'user' => 'User'
            ];
        } elseif (auth()->user()->role === 'staff') {
            $roles = [
                'user' => 'User'
            ];
        } else {
            $roles = [];
        }

        return view('users.create', compact('roles'));
    }

    /**
     * Simpan pengguna baru.
     * NOTE: referral_code dan security_question sudah dihapus dari validasi
     * karena fitur tersebut sudah tidak digunakan di SIPLIN.
     */
    public function store(Request $request)
    {
        if (auth()->user()->role === 'staff') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'phone' => ['nullable', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'max:20'],
                'role' => ['required', 'in:user'],
                'is_active' => ['boolean'],
            ]);
        } elseif (auth()->user()->role === 'admin') {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
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

        $userData = [
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
            'security_setup_completed' => true,
        ];

        try {
            $user = User::create($userData);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna berhasil ditambahkan.'
                ]);
            }

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil ditambahkan.');
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
     * Tampilkan form edit pengguna.
     */
    public function edit(User $user): View
    {
        $roles = [
            'admin' => 'Administrator',
            'staff' => 'Staff',
            'user' => 'User'
        ];

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
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
            'role' => $validated['role'],
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

            $user->update($userData);

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

        // Validasi password kalau admin kirim, generate kalau tidak
        $newPassword = $request->input('password');

        if ($newPassword) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'max:50'],
            ], [
                'password.min' => 'Password minimal 8 karakter.',
                'password.max' => 'Password maksimal 50 karakter.',
            ]);
        } else {
            // Fallback: generate kalau request tidak menyertakan password
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