<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman auth (login/register).
     */
    public function index(Request $request): Response
    {
        // Mode default selalu login, register diakses via toggle di UI
        $mode = $request->query('mode', 'login');
        if (!in_array($mode, ['login', 'register'])) {
            $mode = 'login';
        }

        return response()
            ->view('auth.index', compact('mode'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials['email'] = strtolower(trim($credentials['email']));

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $request->session()->put('last_activity', time());
            $request->session()->save();

            // Logout dari device lain (double login protection)
            $currentSessionId = session()->getId();
            $userId = Auth::id();
            DB::table('sessions')
                ->where('user_id', $userId)
                ->where('id', '!=', $currentSessionId)
                ->delete();

            // Cek apakah user aktif
            if (!Auth::user()->is_active) {
                Auth::logout();

                ActivityLog::create([
                    'user_id' => null,
                    'action' => 'login_failed',
                    'description' => 'Login gagal: Akun tidak aktif - ' . $credentials['email'],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'created_at' => now(),
                ]);

                return back()->withErrors([
                    'email' => 'Akun menunggu verifikasi admin. Silakan coba lagi nanti.',
                ]);
            }

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'description' => 'Login berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Auth::user()->update(['last_login_at' => now()]);

            // LANGSUNG KE DASHBOARD, security setup di-skip
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Login berhasil!');
        }

        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'login_failed',
                'description' => 'Login gagal: Password salah - ' . $credentials['email'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        } else {
            ActivityLog::create([
                'user_id' => null,
                'action' => 'login_failed',
                'description' => 'Login gagal: Email tidak ditemukan - ' . $credentials['email'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Logout user. Redirect ke HOMEPAGE (/), bukan /auth.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'logout',
                'description' => 'Logout berhasil',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke homepage (/), bukan /auth
        return redirect('/')->with('success', 'Anda telah logout.');
    }
}