<x-app-layout title="Edit Pengguna">
    <div class="max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-700 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-xl font-bold text-gray-900 mt-2">Edit Pengguna: {{ $user->name }}</h2>
        </div>

        {{-- ============ HASIL RESET PASSWORD (tampil sekali setelah reset) ============ --}}
        @if(session('reset_password_result'))
        <div class="mb-6 bg-amber-50 border-2 border-amber-300 rounded-lg p-5">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold text-amber-900 mb-2">Password Baru Berhasil Dibuat</p>
                    <p class="text-sm text-amber-800 mb-3">
                        Catat password ini sekarang — password ini <strong>tidak akan ditampilkan lagi</strong> setelah Anda meninggalkan halaman ini.
                        Sampaikan ke pengguna secara langsung (chat, telepon, atau tatap muka), jangan kirim lewat email biasa.
                    </p>
                    <div class="bg-white border border-amber-300 rounded-lg p-3 space-y-1">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Email Pengguna</span>
                            <code class="text-sm font-mono">{{ session('reset_password_result')['email'] }}</code>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">Password Baru</span>
                            <code class="text-base font-mono font-bold text-amber-900">{{ session('reset_password_result')['new_password'] }}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card">
            <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body space-y-4">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Nama Lengkap" name="name" :value="$user->name" required />
                        <x-form.input label="Email" name="email" type="email" :value="$user->email" required />
                    </div>

                    <x-form.input label="No. Telepon" name="phone" :value="$user->phone" />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-form.input label="Password Baru" name="password" type="password" helper="Kosongkan jika tidak ingin mengubah" />
                        <x-form.input label="Konfirmasi Password" name="password_confirmation" type="password" />
                    </div>

                    <x-form.select label="Role" name="role" :value="$user->role" required>
                        @foreach($roles as $value => $label)
                        <option value="{{ $value }}" {{ $user->role == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </x-form.select>

                    <div>
                        <label class="form-label">Avatar</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ $user->avatar_url }}" class="w-16 h-16 rounded-full object-cover" alt="">
                            <input type="file" name="avatar" accept="image/*" class="text-sm">
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-primary-600">
                        <label class="text-sm text-gray-700">Pengguna aktif</label>
                    </div>
                </div>

                <div class="card-footer flex justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        {{-- ============ DANGER ZONE: RESET PASSWORD ============ --}}
        @if(auth()->user()->role === 'admin' && auth()->id() !== $user->id)
        <div class="card mt-6 border-amber-200">
            <div class="card-body">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900">Reset Password Pengguna</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Gunakan ini jika pengguna lupa password. Sistem akan membuatkan password acak baru.
                        </p>
                    </div>
                    <form action="{{ route('users.reset-password', $user) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin reset password untuk {{ $user->name }}? Password lama akan langsung tidak berlaku.');">
                        @csrf
                        <button type="submit" class="btn btn-outline text-amber-700 border-amber-300 hover:bg-amber-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>