<x-guest-layout title="Lupa Password">
    <div class="text-center mb-6">
        <div class="mx-auto flex items-center justify-center w-14 h-14 rounded-full bg-amber-100 mb-4">
            <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Lupa Password?</h2>
        <p class="text-sm text-gray-500 mt-2">
            Untuk alasan keamanan, reset password tidak dapat dilakukan secara mandiri.
        </p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 text-sm text-gray-700 space-y-3">
        <p class="font-medium text-blue-900">Langkah yang perlu dilakukan:</p>
        <ol class="list-decimal list-inside space-y-2 text-gray-600">
            <li>Hubungi administrator sistem SIPLIN di unit Anda</li>
            <li>Sampaikan email akun yang ingin direset passwordnya</li>
            <li>Administrator akan membuatkan password baru untuk Anda</li>
            <li>Login kembali menggunakan password baru, lalu segera ganti melalui menu Profil</li>
        </ol>
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="btn btn-primary w-full inline-block">
            Kembali ke Login
        </a>
    </div>
</x-guest-layout>