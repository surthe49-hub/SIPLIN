<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /**
     * Validate referral code - DEPRECATED.
     * Method ini masih ada karena masih di-reference oleh routes/api.php.
     * Selalu return invalid karena fitur referral sudah dimatikan.
     */
    public function validateReferral(Request $request): JsonResponse
    {
        return response()->json([
            'valid' => false,
            'message' => 'Fitur kode referral sudah tidak digunakan.'
        ]);
    }

    /**
     * Proses registrasi.
     *
     * SECURITY MODEL: Registrasi publik tetap tersedia, namun user baru
     * dibuat dengan is_active=false. Admin harus mengaktifkan akun secara
     * manual sebelum user bisa login. Ini mencegah unauthorized access
     * meskipun endpoint /register bersifat public.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.email' => 'Format email tidak valid.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        try {
            // User dibuat dengan is_active=false. Admin harus approve manual.
            $user = new User([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => bcrypt($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'is_active' => false,
            ]);
            // Role di-set explicit di luar mass assignment untuk defense-in-depth
            $user->role = 'user';
            $user->save();

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'register',
                'description' => 'Registrasi akun baru - menunggu persetujuan admin',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // JANGAN auto-login. User harus tunggu admin approve.
            return redirect()->route('auth')
                ->with('success', 'Registrasi berhasil! Akun Anda menunggu persetujuan administrator. Anda akan bisa login setelah akun diaktifkan.');

        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage(), [
                'input' => $request->except(['password', 'password_confirmation'])
            ]);

            if (app()->environment('local', 'testing')) {
                return back()
                    ->withInput()
                    ->with('error', 'Registrasi gagal: ' . $e->getMessage());
            }

            return back()
                ->withInput()
                ->with('error', 'Registrasi gagal. Silakan coba lagi.');
        }
    }
}