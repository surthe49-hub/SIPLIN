@props(['title' => ''])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title . ' - ' : '' }}{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Force Light Theme inside the UI container -->
    <style>
        body { color-scheme: light !important; }
        .bg-white { background-color: #ffffff !important; }
        
        /* Premium inputs override */
        .input, .form-input, input[type="text"], input[type="email"], input[type="password"], select {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: #0f172a !important;
            border-radius: 0.75rem !important;
            padding: 0.75rem 1rem !important;
            transition: all 0.2s ease-in-out !important;
            width: 100% !important;
        }
        
        .input:focus, .form-input:focus, input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus, select:focus {
            background-color: #ffffff !important;
            border-color: #06b6d4 !important;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.12) !important;
            outline: none !important;
        }

        .input::placeholder, .form-input::placeholder {
            color: #94a3b8 !important;
        }

        /* Modernized Buttons */
        .btn-primary, button[type="submit"] {
            background: linear-gradient(to right, #2563eb, #06b6d4) !important;
            border: none !important;
            color: white !important;
            padding: 0.875rem 1.25rem !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15) !important;
            transition: all 0.2s ease-in-out !important;
            cursor: pointer;
            width: 100% !important;
        }

        .btn-primary:hover, button[type="submit"]:hover {
            transform: translateY(-1px) !important;
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.25) !important;
            filter: brightness(1.05);
        }

        * { color-scheme: light !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 min-h-screen">
        
        {{-- ============ SISI KIRI: FORM AUTHENTICATION ============ --}}
        <div class="lg:col-span-5 flex flex-col justify-between p-8 sm:p-12 md:p-16 bg-white relative shadow-2xl z-10">
            
            <!-- Logo & Brand Header ULP Cilacap -->
            <div class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-pln-no-bg.png') }}" alt="Logo PLN" class="h-9 w-auto transition-transform duration-500 group-hover:rotate-6">
                <div>
                    <span class="font-bold text-slate-950 block leading-none tracking-tight text-base">{{ config('app.name') }}</span>
                    <span class="text-[10px] font-bold text-cyan-600 uppercase tracking-widest block mt-0.5">ULP Cilacap</span>
                </div>
            </div>

            <!-- Form Container Bawaan ($slot dari login/register) -->
            <div class="w-full max-w-md mx-auto my-10 bg-white">
                {{ $slot }}
            </div>

            <!-- Footer Kiri -->
            <div class="text-center lg:text-left text-xs text-slate-400 font-medium">
                &copy; {{ date('Y') }} PT PLN (Persero) &bull; ULP Cilacap
            </div>
        </div>

        {{-- ============ SISI KANAN: BRANDING TECH VISUAL (DARK GLOW) ============ --}}
        <div class="hidden lg:flex lg:col-span-7 bg-gradient-to-br from-slate-950 via-blue-950 to-cyan-900 relative items-center justify-center p-12 overflow-hidden">
            
            <!-- Glow Rings -->
            <div class="absolute w-[600px] h-[600px] bg-cyan-500/10 rounded-full blur-[130px] -top-20 -right-20 animate-pulse"></div>
            <div class="absolute w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[110px] -bottom-20 -left-20"></div>
            
            <!-- Cyber Grid Pattern overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.02)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.02)_1px,transparent_1px)] bg-[size:24px_24px]"></div>

            <div class="relative z-10 text-center space-y-8 max-w-sm">
                <!-- Glassmorphism Logo Wrapper -->
                <div class="inline-block p-8 bg-white/5 backdrop-blur-xl rounded-3xl border border-white/10 shadow-2xl shadow-black/40 transform hover:scale-105 transition-transform duration-500">
                    <img src="{{ asset('images/logo-pln-no-bg.png') }}" alt="Logo PLN Utama" class="h-36 w-auto filter drop-shadow-[0_12px_24px_rgba(6,182,212,0.3)] select-none">
                </div>
                
                <div class="space-y-3">
                    <h2 class="text-2xl font-bold tracking-tight text-white">Sistem Inventaris Barang</h2>
                    <p class="text-sm text-slate-300/80 leading-relaxed">
                        Gerbang otentikasi internal terintegrasi untuk monitoring, mutasi, dan pelaporan sirkulasi aset logistik PLN ULP Cilacap.
                    </p>
                </div>

                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-[11px] font-semibold tracking-wide text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-ping"></span>
                    Koneksi Enkripsi Terproteksi
                </div>
            </div>
        </div>

    </div>

    <!-- Toggle Password Visibility Script Asli -->
    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            const button = input.parentElement.querySelector('button');
            const showIcon = button.querySelector('.password-icon-show');
            const hideIcon = button.querySelector('.password-icon-hide');
            
            if (input.type === 'password') {
                input.type = 'text';
                showIcon.classList.add('hidden');
                hideIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                showIcon.classList.remove('hidden');
                hideIcon.classList.add('hidden');
            }
        }
        
        function togglePassword(inputId, iconId) {
            togglePasswordVisibility(inputId);
        }
    </script>

    @stack('scripts')
</body>
</html>