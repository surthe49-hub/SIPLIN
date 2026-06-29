<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Sistem Inventaris Barang PLN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    {{-- ============ NAVBAR ============ --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    {{-- Ganti src logo sesuai kebutuhan PLN --}}
                    <img src="{{ asset('images/logo-pln.png') }}" alt="Logo PLN" class="h-10 w-auto">
                    <div>
                        <span class="font-bold text-gray-900 block leading-tight">{{ config('app.name') }}</span>
                        <span class="text-xs text-gray-500">PLN ULP Cilacap</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('auth') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-primary-600">
                        Masuk
                    </a>
                    <a href="{{ route('auth') }}?mode=register" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ============ HERO SECTION ============ --}}
    <section class="bg-gradient-to-br from-blue-700 to-blue-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6 leading-tight">
                        Sistem Inventaris Barang<br>
                        <span class="text-blue-200">PLN ULP Cilacap</span>
                    </h1>
                    <p class="text-lg text-blue-100 mb-8">
                        Platform digital untuk pengelolaan aset dan inventaris barang
                        secara terpusat, efisien, dan transparan.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('auth') }}" class="px-6 py-3 bg-white text-blue-700 font-semibold rounded-lg hover:bg-blue-50 transition-colors">
                            Masuk ke Sistem
                        </a>
                        <a href="#fitur" class="px-6 py-3 border-2 border-white text-white font-semibold rounded-lg hover:bg-white/10 transition-colors">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
                <div class="hidden lg:flex justify-center">
                    <img src="{{ asset('images/logo-pln.png') }}" alt="Logo PLN" class="h-64 w-auto opacity-90">
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FITUR SECTION ============ --}}
    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur Utama</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Solusi lengkap untuk pencatatan, pemantauan, dan pelaporan aset PLN.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Barang</h3>
                    <p class="text-sm text-gray-600">Pencatatan inventaris lengkap dengan kategori, lokasi, dan kondisi barang.</p>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Transfer Barang</h3>
                    <p class="text-sm text-gray-600">Kelola perpindahan aset antar lokasi dengan pencatatan riwayat lengkap.</p>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m0 0l-3-3m3 3l-3 3"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pemeliharaan</h3>
                    <p class="text-sm text-gray-600">Jadwal dan riwayat maintenance aset untuk menjaga kualitas barang.</p>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan & Statistik</h3>
                    <p class="text-sm text-gray-600">Laporan dapat di-export ke PDF dengan filter periode dan kategori.</p>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Multi Lokasi</h3>
                    <p class="text-sm text-gray-600">Pengelolaan inventaris tersebar di berbagai gedung dan ruangan.</p>
                </div>

                <div class="p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Keamanan Akun</h3>
                    <p class="text-sm text-gray-600">Sistem otentikasi dengan kontrol akses berdasarkan peran pengguna.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ CTA SECTION ============ --}}
    <section class="bg-blue-50 py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Mulai Kelola Inventaris</h2>
            <p class="text-gray-600 mb-8">Masuk ke sistem untuk mulai mengelola aset PLN dengan mudah.</p>
            <a href="{{ route('auth') }}" class="inline-block px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                Masuk Sekarang
            </a>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-gray-900 text-gray-400 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm">
            &copy; {{ date('Y') }} {{ config('app.name') }} &bull; PLN ULP Cilacap
        </div>
    </footer>

    {{-- Flash message dari logout --}}
    @if(session('success'))
    <div id="flash-message" class="fixed bottom-6 right-6 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => {
            const el = document.getElementById('flash-message');
            if (el) el.remove();
        }, 3500);
    </script>
    @endif

</body>
</html>