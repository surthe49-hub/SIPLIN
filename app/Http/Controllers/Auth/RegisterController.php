<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Validate referral code - DEPRECATED tapi tetap ada untuk backward compat.
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
     * Proses registrasi tanpa referral, tanpa security questions.
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
            $user = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null,
                'role' => 'user', // default role staff/user
                'is_active' => true,
                'security_setup_completed' => true, // langsung true, skip setup
            ]);

            Auth::login($user);

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'register',
                'description' => 'Registrasi akun baru',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // LANGSUNG KE DASHBOARD, skip security setup
            return redirect()->route('dashboard')
                ->with('success', 'Registrasi berhasil! Selamat datang.');

        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage(), [
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

    /**
     * METHOD DI-DISABLE - security setup tidak dipakai.
     * Method tetap ada agar route tidak error, langsung redirect dashboard.
     */
    public function showSetupSecurity(): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    public function storeSetupSecurity(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard');
    }
}