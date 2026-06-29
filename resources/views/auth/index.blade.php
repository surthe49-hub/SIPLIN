<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ──────────────────────────────────────────
           RESET / BASE
        ────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; color-scheme: light dark; }

        body {
            margin: 0;
            min-height: 100svh;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 45%, #0f2d4a 100%);
            display: flex;
            align-items: stretch;
        }

        /* ──────────────────────────────────────────
           LAYOUT SHELL
        ────────────────────────────────────────── */
        .auth-shell {
            display: flex;
            width: 100%;
            min-height: 100svh;
        }

        /* ──────────────────────────────────────────
           LEFT  ─  FORM PANEL
        ────────────────────────────────────────── */
        .form-panel {
            flex: 0 0 480px;
            width: 480px;
            display: flex;
            flex-direction: column;
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid rgba(255,255,255,0.08);
            position: relative;
            z-index: 2;
        }

        @media (max-width: 1023px) {
            .form-panel { flex: 1 1 100%; width: 100%; border-right: none; }
            .brand-panel { display: none !important; }
        }

        /* top bar */
        .form-topbar {
            padding: 20px 32px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .form-topbar img { height: 36px; width: auto; }
        .form-topbar .app-name { font-size: 14px; font-weight: 700; color: #fff; letter-spacing: 0.01em; }
        .form-topbar .app-sub  { font-size: 11px; color: rgba(255,255,255,0.45); margin-top: 1px; letter-spacing: 0.05em; text-transform: uppercase; }

        /* scrollable body */
        .form-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 32px;
            overflow-y: auto;
        }

        /* card */
        .auth-card {
            width: 100%;
            max-width: 420px;
        }

        .auth-card h2 {
            font-size: 26px;
            font-weight: 800;
            color: #fff;
            margin: 0 0 4px;
            letter-spacing: -0.02em;
        }
        .auth-card .sub {
            font-size: 13.5px;
            color: rgba(255,255,255,0.5);
            margin: 0 0 28px;
        }

        /* ──────────────────────────────────────────
           FORM ELEMENTS
        ────────────────────────────────────────── */
        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: rgba(255,255,255,0.65);
            margin-bottom: 6px;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .input-wrap { position: relative; }

        .f-input {
            width: 100%;
            padding: 11px 14px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .f-input::placeholder { color: rgba(255,255,255,0.28); }
        .f-input:focus {
            border-color: #38bdf8;
            background: rgba(56,189,248,0.08);
            box-shadow: 0 0 0 3px rgba(56,189,248,0.15);
        }
        .f-input.has-icon { padding-right: 42px; }
        .f-input.error { border-color: #f87171; }

        .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255,255,255,0.35);
            padding: 0;
            display: flex;
            align-items: center;
            transition: color .15s;
        }
        .eye-btn:hover { color: rgba(255,255,255,0.75); }
        .eye-btn svg { width: 18px; height: 18px; }

        /* remember + forgot row */
        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 13px;
            color: rgba(255,255,255,0.55);
            user-select: none;
        }
        .remember-label input[type=checkbox] {
            width: 15px; height: 15px;
            accent-color: #38bdf8;
            cursor: pointer;
        }
        .forgot-link {
            font-size: 13px;
            color: #38bdf8;
            text-decoration: none;
            transition: color .15s;
        }
        .forgot-link:hover { color: #7dd3fc; }

        /* submit button */
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
            color: #fff;
            font-size: 14.5px;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            letter-spacing: 0.02em;
            transition: opacity .2s, transform .1s, box-shadow .2s;
            box-shadow: 0 4px 18px rgba(14,165,233,0.35);
        }
        .btn-submit:hover  { opacity: .92; box-shadow: 0 6px 24px rgba(14,165,233,0.5); }
        .btn-submit:active { transform: scale(.985); }
        .btn-submit:disabled { opacity: .55; cursor: not-allowed; }

        /* switch link */
        .switch-text {
            text-align: center;
            font-size: 13px;
            color: rgba(255,255,255,0.45);
            margin-top: 22px;
        }
        .switch-text button {
            background: none;
            border: none;
            color: #38bdf8;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            transition: color .15s;
        }
        .switch-text button:hover { color: #7dd3fc; }

        /* alert banners */
        .alert {
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }
        .alert-success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; }
        .alert-error   { background: rgba(248,113,113,0.1);  border: 1px solid rgba(248,113,113,0.3); color: #fca5a5; }
        .alert svg { flex-shrink: 0; width: 16px; height: 16px; margin-top: 1px; }
        .field-error { font-size: 11.5px; color: #f87171; margin-top: 5px; }

        /* 2-col grid for register */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* ──────────────────────────────────────────
           PANEL TOGGLE
        ────────────────────────────────────────── */
        .auth-panel { display: none; }
        .auth-panel.active { display: block; }

        /* ──────────────────────────────────────────
           FOOTER
        ────────────────────────────────────────── */
        .form-footer {
            padding: 16px 32px;
            text-align: center;
            font-size: 11.5px;
            color: rgba(255,255,255,0.2);
            border-top: 1px solid rgba(255,255,255,0.06);
            letter-spacing: 0.02em;
        }

        /* ──────────────────────────────────────────
           RIGHT  ─  BRAND PANEL
        ────────────────────────────────────────── */
        .brand-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        /* animated mesh blobs */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            opacity: 0.18;
            animation: drift 12s ease-in-out infinite alternate;
        }
        .blob-1 { width: 420px; height: 420px; background: #38bdf8; top: -80px; right: -60px; animation-duration: 14s; }
        .blob-2 { width: 300px; height: 300px; background: #2563eb; bottom: 60px; left: 40px; animation-duration: 10s; animation-delay: -3s; }
        .blob-3 { width: 200px; height: 200px; background: #06b6d4; top: 50%; left: 50%; transform: translate(-50%,-50%); animation-duration: 8s; animation-delay: -6s; }

        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, 20px) scale(1.08); }
        }

        /* logo card */
        .brand-logo-card {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 24px;
            padding: 36px 44px;
            text-align: center;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 32px 80px rgba(0,0,0,0.35);
            animation: fadeUp .7s ease both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .brand-logo-card img { height: 120px; width: auto; margin: 0 auto 24px; display: block; }
        .brand-logo-card h1 { font-size: 22px; font-weight: 800; color: #fff; margin: 0 0 6px; letter-spacing: -0.02em; }
        .brand-logo-card p  { font-size: 13px; color: rgba(255,255,255,0.45); margin: 0; }

        /* stats pills */
        .brand-stats {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 12px;
            margin-top: 28px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .stat-pill {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 8px 18px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: fadeUp .7s ease both;
        }
        .stat-pill:nth-child(2) { animation-delay: .1s; }
        .stat-pill:nth-child(3) { animation-delay: .2s; }
        .stat-pill .dot { width: 6px; height: 6px; border-radius: 50%; background: #38bdf8; box-shadow: 0 0 8px #38bdf8; }
        .stat-pill span { font-size: 12px; color: rgba(255,255,255,0.55); }
        .stat-pill strong { font-size: 12px; color: #fff; }
    </style>
</head>
<body x-data="{ mode: '{{ $mode ?? 'login' }}' }">
<div class="auth-shell">

    <!-- ═══════════════════════════════════════
         LEFT — FORM PANEL
    ════════════════════════════════════════ -->
    <div class="form-panel">

        <!-- top bar -->
        <div class="form-topbar">
            <img src="{{ asset('images/logo-pln-no-bg.png') }}" alt="Logo PLN">
            <div>
                <div class="app-name">{{ config('app.name') }}</div>
                <div class="app-sub">PLN ULP Cilacap</div>
            </div>
        </div>

        <!-- scrollable body -->
        <div class="form-body">
            <div class="auth-card">

                <!-- ─── LOGIN ─── -->
                <div class="auth-panel {{ ($mode ?? 'login') === 'login' ? 'active' : '' }}" id="panel-login">
                    <h2>Masuk ke Akun</h2>
                    <p class="sub">Silakan masukkan kredensial Anda</p>

                    @if(session('success'))
                    <div class="alert alert-success">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-error">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" id="loginForm" onsubmit="return handleLoginSubmit(this)">
                        @csrf
                        <input type="hidden" name="_token_timestamp" value="{{ time() }}">

                        <div class="field">
                            <label for="loginEmail">Email</label>
                            <input id="loginEmail" type="email" name="email" value="{{ old('email') }}"
                                   class="f-input {{ $errors->has('email') ? 'error' : '' }}"
                                   placeholder="nama@pln.co.id" required autofocus>
                            @error('email')<div class="field-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label for="loginPassword">Password</label>
                            <div class="input-wrap">
                                <input id="loginPassword" type="password" name="password"
                                       class="f-input has-icon {{ $errors->has('password') ? 'error' : '' }}"
                                       placeholder="••••••••" required>
                                <button type="button" class="eye-btn" onclick="togglePwd('loginPassword', this)" tabindex="-1">
                                    <svg class="icon-show" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg class="icon-hide" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.27 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                            @error('password')<div class="field-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="meta-row">
                            <label class="remember-label">
                                <input type="checkbox" name="remember">
                                Ingat saya
                            </label>
                            <a href="{{ route('password.reset.auth') }}" class="forgot-link">Lupa password?</a>
                        </div>

                        <button type="submit" id="loginBtn" class="btn-submit">Masuk</button>
                    </form>

                    <p class="switch-text">
                        Belum punya akun?
                        <button type="button" onclick="switchPanel('register')">Daftar</button>
                    </p>
                </div><!-- /panel-login -->

                <!-- ─── REGISTER ─── -->
                <div class="auth-panel {{ ($mode ?? 'login') === 'register' ? 'active' : '' }}" id="panel-register">
                    <h2>Buat Akun Baru</h2>
                    <p class="sub">Lengkapi data untuk mendaftar</p>

                    @if(session('error'))
                    <div class="alert alert-error">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12A9 9 0 113 12a9 9 0 0118 0z"/></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="field">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="f-input {{ $errors->has('name') ? 'error' : '' }}"
                                   placeholder="Nama sesuai ID" required>
                            @error('name')<div class="field-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="f-input {{ $errors->has('email') ? 'error' : '' }}"
                                   placeholder="nama@pln.co.id" required>
                            @error('email')<div class="field-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="field">
                            <label>No. Telepon <span style="font-weight:400;opacity:.5">(opsional)</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                   class="f-input {{ $errors->has('phone') ? 'error' : '' }}"
                                   placeholder="08xxxxxxxxxx">
                            @error('phone')<div class="field-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="grid-2">
                            <div class="field">
                                <label>Password</label>
                                <div class="input-wrap">
                                    <input id="regPwd" type="password" name="password"
                                           class="f-input has-icon {{ $errors->has('password') ? 'error' : '' }}"
                                           placeholder="••••••••" required>
                                    <button type="button" class="eye-btn" onclick="togglePwd('regPwd', this)" tabindex="-1">
                                        <svg class="icon-show" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg class="icon-hide" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.27 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                                @error('password')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="field">
                                <label>Konfirmasi</label>
                                <div class="input-wrap">
                                    <input id="regPwdConf" type="password" name="password_confirmation"
                                           class="f-input has-icon"
                                           placeholder="••••••••" required>
                                    <button type="button" class="eye-btn" onclick="togglePwd('regPwdConf', this)" tabindex="-1">
                                        <svg class="icon-show" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <svg class="icon-hide" style="display:none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.27 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" style="margin-top:6px">Daftar Sekarang</button>
                    </form>

                    <p class="switch-text">
                        Sudah punya akun?
                        <button type="button" onclick="switchPanel('login')">Masuk</button>
                    </p>
                </div><!-- /panel-register -->

            </div><!-- /auth-card -->
        </div><!-- /form-body -->

        <div class="form-footer">
            &copy; {{ date('Y') }} PLN ULP CILACAP &bull; {{ config('app.name') }}
        </div>
    </div><!-- /form-panel -->

    <!-- ═══════════════════════════════════════
         RIGHT — BRAND PANEL
    ════════════════════════════════════════ -->
    <div class="brand-panel">
        <!-- animated blobs -->
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>

        <!-- logo card -->
        <div class="brand-logo-card">
            <img src="{{ asset('images/logo-pln-no-bg.png') }}" alt="Logo PLN">
            <h1>Sistem Inventaris Barang</h1>
            <p>PLN ULP Cilacap</p>
        </div>

        <!-- stats pills -->
        <div class="brand-stats">
            <div class="stat-pill">
                <div class="dot"></div>
                <strong>100%</strong>
                <span>Aset Terdata Valid</span>
            </div>
            <div class="stat-pill">
                <div class="dot"></div>
                <strong>Real-Time</strong>
                <span>Pelacakan Mutasi</span>
            </div>
            <div class="stat-pill">
                <div class="dot"></div>
                <strong>Paperless</strong>
                <span>Pelaporan PDF</span>
            </div>
        </div>
    </div><!-- /brand-panel -->

</div><!-- /auth-shell -->

<script>
    /* ── panel switch ── */
    function switchPanel(name) {
        document.querySelectorAll('.auth-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('panel-' + name).classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    /* ── password toggle ── */
    function togglePwd(inputId, btn) {
        const inp = document.getElementById(inputId);
        if (!inp) return;
        const isHidden = inp.type === 'password';
        inp.type = isHidden ? 'text' : 'password';
        btn.querySelector('.icon-show').style.display = isHidden ? 'none' : '';
        btn.querySelector('.icon-hide').style.display = isHidden ? ''     : 'none';
    }

    /* ── login submit guard ── */
    let loginSubmitted = false;
    function handleLoginSubmit(form) {
        if (loginSubmitted) return false;
        loginSubmitted = true;
        const btn = document.getElementById('loginBtn');
        if (btn) { btn.disabled = true; btn.textContent = 'Memproses…'; }
        setTimeout(() => {
            loginSubmitted = false;
            if (btn) { btn.disabled = false; btn.textContent = 'Masuk'; }
        }, 6000);
        return true;
    }

    /* ── SweetAlert notifications ── */
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon:'success', title:'Berhasil!', text:'{{ session('success') }}',
                timer:3000, showConfirmButton:false, toast:true, position:'top-end' });
        }
        @endif
        @if(session('error'))
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon:'error', title:'Gagal!', text:'{{ session('error') }}',
                timer:5000, showConfirmButton:true, confirmButtonColor:'#dc2626' });
        }
        @endif
        @if($errors->any())
        const errs = [];
        @foreach($errors->all() as $error) errs.push('{{ $error }}'); @endforeach
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon:'error', title:'Validasi Gagal!', html:errs.join('<br>'),
                timer:5000, showConfirmButton:true, confirmButtonColor:'#dc2626' });
        }
        @if($errors->any() && ($mode ?? 'login') === 'register')
        switchPanel('register');
        @endif
        @endif
    });

    /* ── back-forward cache fix ── */
    window.addEventListener('pageshow', e => { if (e.persisted) window.location.reload(); });
</script>
</body>
</html>