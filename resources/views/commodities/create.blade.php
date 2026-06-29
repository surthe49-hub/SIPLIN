<x-app-layout title="Tambah Barang">
    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('commodities.index') }}" class="text-sm hover:underline flex items-center gap-1" style="color: var(--text-secondary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h2 class="text-2xl font-bold mt-2" style="color: var(--text-primary);">Tambah Barang Baru</h2>
        </div>

        <form action="{{ route('commodities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- HIDDEN DEFAULTS untuk field yang dihapus dari UI tapi masih divalidasi backend --}}
            <input type="hidden" name="acquisition_type" value="pembelian">
            <input type="hidden" name="purchase_price" value="0">

            <!-- Grid Layout: 2 Kolom Utama -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <!-- Left Column (2/3 width) -->
                <div class="xl:col-span-2 space-y-6">

                    <!-- Informasi Dasar -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold" style="color: var(--text-primary);">Informasi Dasar</h3>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <x-form.input label="Nama Barang" name="name" required placeholder="Masukkan nama barang" />
                            </div>
                            <div>
                                <label class="form-label">Kode Barang</label>
                                <div class="flex gap-2">
                                    <input type="text" name="item_code" id="itemCodeInput" class="form-input font-mono flex-1"
                                           placeholder="Otomatis berdasarkan kategori" value="{{ old('item_code') }}"
                                           style="font-family: 'Consolas', 'Monaco', 'Courier New', monospace;">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    <span id="codeHint">Pilih kategori untuk generate otomatis atau input manual</span>
                                </p>
                                @error('item_code')
                                <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Kategori <span class="text-red-500">*</span></label>
                                <select name="category_id" id="categorySelect" class="form-input" required onchange="updateItemCodePreview()">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-code="{{ $category->code }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} {{ $category->code ? "({$category->code})" : '' }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <p class="form-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                                    Lokasi <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-3">
                                    <select name="location_id" id="locationSelect" class="input w-full" onchange="toggleCustomLocation()">
                                        <option value="">Pilih dari daftar lokasi</option>
                                        @foreach($locations as $location)
                                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }} - {{ $location->building ?? 'Gedung' }} {{ $location->floor ?? '' }} {{ $location->room ?? '' }}
                                        </option>
                                        @endforeach
                                        <option value="custom" {{ old('location_id') == 'custom' ? 'selected' : '' }}>🏷️ Input Manual / Lainnya</option>
                                    </select>

                                    <div id="customLocationInput" class="hidden">
                                        <input type="text" name="custom_location" id="customLocation"
                                               placeholder="Contoh: Ruang Server Lt.3, Gudang Belakang, dll..."
                                               class="input w-full"
                                               value="{{ old('custom_location') }}">
                                        <p class="text-xs mt-1" style="color: var(--text-secondary);">Masukkan lokasi sesuai kebutuhan</p>
                                    </div>
                                </div>
                                @error('location_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                                @error('custom_location')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
                            <x-form.input label="Merk/Brand" name="brand" placeholder="Contoh: HP, Dell" />
                            <x-form.input label="Model/Tipe" name="model" placeholder="Contoh: Pavilion 14" />
                            <x-form.input label="Serial Number" name="serial_number" placeholder="Nomor seri (opsional)" />
                        </div>
                    </div>

                    <!-- Detail Barang (disederhanakan) -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Detail Barang</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                            <x-form.input label="Tahun Perolehan" name="purchase_year" type="number" min="1900" max="{{ date('Y') }}" placeholder="{{ date('Y') }}" />
                            <x-form.input label="Jumlah" name="quantity" type="number" min="1" value="1" required />
                            <x-form.select label="Kondisi" name="condition" required :options="[
                                'baik' => 'Baik',
                                'rusak_ringan' => 'Rusak Ringan',
                                'rusak_berat' => 'Rusak Berat'
                            ]" />
                        </div>
                    </div>

                </div>

                <!-- Right Column (1/3 width) -->
                <div class="space-y-6">

                    <!-- Foto Barang -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Foto Barang</h3>
                        <div>
                            <input type="file" name="images[]" multiple accept="image/*,application/pdf,.doc,.docx" class="input w-full">
                            <p class="text-xs mt-2" style="color: var(--text-secondary);">
                                Upload maksimal 5 file (JPG, PNG, PDF, DOC max 2MB per file). Gambar pertama akan menjadi foto utama.
                            </p>
                            @error('images.*')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="theme-card rounded-xl border p-6" style="background-color: var(--bg-card); border-color: var(--border-color);">
                        <h3 class="text-lg font-semibold mb-4" style="color: var(--text-primary);">Informasi Tambahan</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Spesifikasi</label>
                                <textarea name="specifications" rows="3" class="input w-full" placeholder="Spesifikasi teknis barang (opsional)">{{ old('specifications') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--text-primary);">Catatan</label>
                                <textarea name="notes" rows="3" class="input w-full" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Submit Actions -->
            <div class="flex justify-end gap-3 pt-6">
                <a href="{{ route('commodities.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Barang
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        // Toggle custom location input
        function toggleCustomLocation() {
            const select = document.getElementById('locationSelect');
            const customInput = document.getElementById('customLocationInput');
            const customField = document.getElementById('customLocation');

            if (select.value === 'custom') {
                customInput.classList.remove('hidden');
                customField.setAttribute('required', 'required');
            } else {
                customInput.classList.add('hidden');
                customField.removeAttribute('required');
                customField.value = '';
            }
        }

        // Update item code when category changes
        function updateItemCodePreview() {
            const categorySelect = document.getElementById('categorySelect');
            const codeHint = document.getElementById('codeHint');
            const itemCodeInput = document.getElementById('itemCodeInput');
            const categoryId = categorySelect.value;

            if (categoryId) {
                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                const categoryCode = selectedOption.getAttribute('data-code') || 'INV';
                codeHint.textContent = `Format: ${categoryCode}-${new Date().getFullYear()}-XXXX`;

                const shouldGenerate = !itemCodeInput.value ||
                                     itemCodeInput.value === 'Loading...' ||
                                     itemCodeInput.value === 'Otomatis berdasarkan kategori' ||
                                     itemCodeInput.getAttribute('data-auto-generated') !== 'false';

                if (shouldGenerate) {
                    itemCodeInput.value = 'Loading...';
                    itemCodeInput.disabled = true;

                    fetch(`{{ route('commodities.preview-code') }}?category_id=${categoryId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            itemCodeInput.value = data.code;
                            itemCodeInput.setAttribute('data-auto-generated', 'true');
                            itemCodeInput.disabled = false;
                        })
                        .catch(error => {
                            itemCodeInput.value = '';
                            itemCodeInput.setAttribute('data-auto-generated', 'false');
                            itemCodeInput.disabled = false;
                        });
                }
            } else {
                codeHint.textContent = 'Pilih kategori untuk generate otomatis atau input manual';
                if (itemCodeInput.getAttribute('data-auto-generated') === 'true') {
                    itemCodeInput.value = '';
                    itemCodeInput.setAttribute('data-auto-generated', 'false');
                }
            }
        }

        function trackManualInput() {
            const itemCodeInput = document.getElementById('itemCodeInput');
            itemCodeInput.addEventListener('input', function() {
                if (this.value && this.value !== 'Loading...') {
                    this.setAttribute('data-auto-generated', 'false');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleCustomLocation();
            trackManualInput();
            const categorySelect = document.getElementById('categorySelect');
            if (categorySelect.value) {
                updateItemCodePreview();
            }
        });
    </script>
    @endpush
</x-app-layout>