<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Sistem Inventaris Barang PLN</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo-pln-no-bg.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-pln-no-bg.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-blue-500 selection:text-white">

    {{-- ============ NAVBAR ============ --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo-pln-no-bg.png') }}" alt="Logo PLN" class="h-10 w-auto transition-transform duration-500 group-hover:rotate-12">
                    <div>
                        <span class="font-bold text-slate-900 block leading-tight tracking-tight">{{ config('app.name') }}</span>
                        <span class="text-xs font-medium text-cyan-600">PLN ULP Cilacap</span>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('auth') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('auth') }}?mode=register" class="px-5 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-blue-500/10 hover:shadow-lg hover:shadow-blue-500/20 transition-all duration-300 transform hover:-translate-y-0.5">
                        Daftar Akses
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ============ HERO SECTION ============ --}}
    <section class="relative bg-gradient-to-br from-slate-950 via-blue-950 to-cyan-900 text-white overflow-hidden py-24 lg:py-32">
        <!-- Ornamen dekoratif latar belakang samar -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(6,182,212,0.15),transparent_45%)]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-xs font-semibold tracking-wide text-cyan-400 uppercase">
                        ⚡ Logistik Terintegrasi v2.0
                    </div>
                    <h1 class="text-4xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                        Sistem Inventaris <br class="hidden sm:inline">
                        <span class="bg-gradient-to-r from-cyan-400 via-blue-400 to-indigo-200 bg-clip-text text-transparent">Aset & Barang</span>
                    </h1>
                    <p class="text-lg text-slate-300/90 leading-relaxed max-w-xl">
                        Optimalkan manajemen logistik, lacak status pergudangan, dan pantau mutasi inventaris secara real-time di lingkungan internal PLN ULP Cilacap.
                    </p>
                    <div class="flex flex-wrap gap-4 pt-2">
                        <a href="{{ route('auth') }}" class="px-6 py-3.5 bg-gradient-to-r from-cyan-500 to-blue-600 font-semibold rounded-xl shadow-lg shadow-cyan-500/20 hover:from-cyan-600 hover:to-blue-700 transition-all duration-300 transform hover:-translate-y-0.5">
                            Buka Dashboard Sistem
                        </a>
                        <a href="#alur-kerja" class="px-6 py-3.5 border border-slate-700 bg-slate-900/40 font-semibold rounded-xl hover:bg-slate-800/60 hover:border-slate-600 transition-all duration-300">
                            Lihat Alur Kerja
                        </a>
                    </div>
                </div>
                <div class="hidden lg:flex lg:col-span-5 justify-center relative">
                    <!-- Glassmorphism Effect Backplate -->
                    <div class="absolute w-72 h-72 bg-blue-500/10 rounded-full blur-3xl -z-10 animate-pulse"></div>
                    <div class="p-8 bg-white/5 backdrop-blur-md rounded-3xl border border-white/10 shadow-2xl shadow-black/40">
                        <img src="{{ asset('images/logo-pln-no-bg.png') }}" alt="Logo PLN" class="h-56 w-auto filter drop-shadow-[0_10px_15px_rgba(6,182,212,0.3)] select-none">
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ STATS BAR (PENGGANTI PENJELASAN) ============ --}}
    <section class="relative -mt-8 max-w-5xl mx-auto px-4 sm:px-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 overflow-hidden">
            <div class="p-6 text-center sm:text-left sm:px-8">
                <span class="block text-3xl font-extrabold text-slate-900 tracking-tight">100%</span>
                <span class="text-sm font-medium text-slate-500 mt-1 block">Aset Terdata Valid</span>
            </div>
            <div class="p-6 text-center sm:text-left sm:px-8">
                <span class="block text-3xl font-extrabold text-slate-900 tracking-tight">Real-Time</span>
                <span class="text-sm font-medium text-slate-500 mt-1 block">Pelacakan Mutasi</span>
            </div>
            <div class="p-6 text-center sm:text-left sm:px-8">
                <span class="block text-3xl font-extrabold text-slate-900 tracking-tight">Paperless</span>
                <span class="text-sm font-medium text-slate-500 mt-1 block">Pelaporan PDF Ekspor</span>
            </div>
        </div>
    </section>

    {{-- ============ ALUR KERJA LOGISTIK ============ --}}
    <section id="alur-kerja" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight sm:text-4xl">Siklus Operasional Aset</h2>
                <p class="text-slate-600">Bagaimana sistem mengamankan dan mengontrol sirkulasi inventaris di lapangan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Step 1 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/60 shadow-sm relative group hover:shadow-md transition-shadow duration-300">
                    <span class="absolute top-6 right-8 text-5xl font-black text-slate-100 select-none group-hover:text-blue-50 transition-colors">01</span>
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-bold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Pencatatan Masuk</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Barang baru diidentifikasi berdasarkan kategori, kondisi awal, spesifikasi teknis, serta penempatan ruangan.</p>
                </div>

                <!-- Step 2 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/60 shadow-sm relative group hover:shadow-md transition-shadow duration-300">
                    <span class="absolute top-6 right-8 text-5xl font-black text-slate-100 select-none group-hover:text-cyan-50 transition-colors">02</span>
                    <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-xl flex items-center justify-center font-bold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Monitoring & Mutasi</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Perpindahan posisi unit atau maintenance berkala tercatat secara historis terpusat untuk menghindari hilangnya aset data.</p>
                </div>

                <!-- Step 3 -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200/60 shadow-sm relative group hover:shadow-md transition-shadow duration-300">
                    <span class="absolute top-6 right-8 text-5xl font-black text-slate-100 select-none group-hover:text-indigo-50 transition-colors">03</span>
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center font-bold mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0l-3-3m3 3l-3 3"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Pelaporan Ter-Audit</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">Data rekapitulasi dapat diekspor menjadi berkas laporan resmi guna penyelarasan stok opname bulanan/tahunan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CTA SECTION ============ --}}
    <section class="bg-white py-20 border-t border-slate-200/60">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight sm:text-4xl">Siap Melakukan Pengelolaan?</h2>
            <p class="text-slate-600 max-w-lg mx-auto leading-relaxed">Gunakan hak akses akun Anda untuk mengelola otentikasi data, peminjaman, dan peninjauan stok barang.</p>
            <div class="pt-2">
                <a href="{{ route('auth') }}" class="inline-block px-8 py-3.5 bg-slate-950 hover:bg-slate-900 text-white font-semibold rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                    Masuk ke Akun Saya
                </a>
            </div>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-slate-950 text-slate-500 py-12 border-t border-slate-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm">
            <div>
                &copy; {{ date('Y') }} {{ config('app.name') }} &bull; PT PLN (Persero) ULP Cilacap.
            </div>
            <div class="text-slate-600 text-xs">
                Hak Cipta Dilindungi &bull; Internal Unit Kebijakan Logistik
            </div>
        </div>
    </footer>

    {{-- Flash message dari logout --}}
    @if(session('success'))
    <div id="flash-message" class="fixed bottom-6 right-6 bg-emerald-600 text-white px-6 py-3.5 rounded-xl shadow-xl shadow-emerald-900/20 z-50 transition-all duration-500 animate-bounce">
        <div class="flex items-center gap-2 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
    <script>
        setTimeout(() => {
            const el = document.getElementById('flash-message');
            if (el) {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            }
        }, 3500);
    </script>
    @endif

</body>
</html>