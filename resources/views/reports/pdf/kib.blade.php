<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }} - {{ $commodity->item_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }

        /* ============ LETTERHEAD ============ */
        .letterhead {
            border-bottom: 3px solid #003399;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .letterhead-inner {
            display: table;
            width: 100%;
        }
        .letterhead-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }
        .letterhead-logo img {
            width: 64px;
            height: auto;
        }
        .letterhead-text {
            display: table-cell;
            vertical-align: middle;
            padding-left: 12px;
        }
        .letterhead-text .org-name {
            font-size: 13pt;
            font-weight: bold;
            color: #003399;
            letter-spacing: 0.5px;
        }
        .letterhead-text .org-unit {
            font-size: 10pt;
            font-weight: bold;
            color: #333;
            margin-top: 2px;
        }
        .letterhead-text .org-address {
            font-size: 8pt;
            color: #555;
            margin-top: 3px;
        }
        .letterhead-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 140px;
        }
        .letterhead-right .system-name {
            font-size: 9pt;
            color: #003399;
            font-weight: bold;
        }
        .letterhead-right .system-sub {
            font-size: 8pt;
            color: #666;
        }

        /* ============ DOC TITLE ============ */
        .doc-title {
            text-align: center;
            margin-bottom: 14px;
        }
        .doc-title h1 {
            font-size: 14pt;
            font-weight: bold;
            color: #003399;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .doc-title .doc-subtitle {
            font-size: 9pt;
            color: #555;
            margin-top: 4px;
        }
        .doc-divider {
            border: none;
            border-top: 1px solid #ccc;
            margin: 8px 0 16px 0;
        }

        /* ============ KIB HERO (kode + foto) ============ */
        .kib-hero {
            display: table;
            width: 100%;
            margin-bottom: 18px;
        }
        .kib-hero-info {
            display: table-cell;
            vertical-align: top;
            padding-right: 16px;
        }
        .kib-hero-code {
            background-color: #003399;
            color: white;
            padding: 8px 14px;
            font-size: 11pt;
            font-weight: bold;
            font-family: monospace;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: inline-block;
        }
        .kib-hero-name {
            font-size: 14pt;
            font-weight: bold;
            color: #003399;
            margin-bottom: 4px;
        }
        .kib-hero-sub {
            font-size: 9pt;
            color: #666;
        }
        .kib-hero-photo {
            display: table-cell;
            vertical-align: top;
            width: 180px;
            text-align: right;
        }
        .kib-hero-photo img {
            width: 170px;
            height: 130px;
            object-fit: cover;
            border: 1px solid #d0d8f0;
            padding: 3px;
            background-color: #fafafa;
        }
        .kib-hero-photo .no-photo {
            width: 170px;
            height: 130px;
            border: 1px dashed #ccc;
            background-color: #fafafa;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            font-size: 8pt;
            color: #999;
            font-style: italic;
        }

        /* ============ SECTION TITLE ============ */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            color: white;
            background-color: #003399;
            padding: 6px 10px;
            margin-bottom: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ============ INFO TABLE ============ */
        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        table.info td {
            padding: 6px 10px;
            border: 1px solid #d0d8f0;
            vertical-align: top;
            font-size: 9.5pt;
        }
        table.info .label {
            font-weight: bold;
            background-color: #f5f8ff;
            width: 30%;
            color: #003399;
        }
        table.info .value {
            background-color: #ffffff;
        }

        /* ============ BADGE ============ */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 3px;
            font-size: 8.5pt;
            font-weight: bold;
        }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger  { background-color: #f8d7da; color: #721c24; }

        /* ============ NOTES / SPEC BLOCK ============ */
        .note-block {
            background-color: #fafafa;
            border: 1px solid #d0d8f0;
            border-top: none;
            padding: 10px 12px;
            font-size: 9pt;
            white-space: pre-wrap;
            word-wrap: break-word;
            margin-bottom: 14px;
        }

        /* ============ MAINTENANCE TABLE ============ */
        table.maintenance {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 14px;
        }
        table.maintenance th,
        table.maintenance td {
            border: 1px solid #d0d8f0;
            padding: 5px 8px;
            text-align: left;
        }
        table.maintenance th {
            background-color: #003399;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        table.maintenance tr:nth-child(even) {
            background-color: #f2f5ff;
        }
        table.maintenance tfoot th {
            background-color: #e8ecf8;
            color: #003399;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        /* ============ SIGNATURE ============ */
        .signature-area {
            margin-top: 30px;
            page-break-inside: avoid;
            font-size: 9pt;
        }
        .signature-right {
            text-align: center;
            width: 240px;
            float: right;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 55px;
            margin-bottom: 5px;
        }

        /* ============ FOOTER ============ */
        .page-footer {
            margin-top: 30px;
            padding-top: 8px;
            border-top: 1px solid #ccc;
            font-size: 7.5pt;
            color: #888;
        }
        .page-footer table {
            width: 100%;
        }
    </style>
</head>
<body>

    {{-- ============ LETTERHEAD ============ --}}
    <div class="letterhead">
        <div class="letterhead-inner">
            <div class="letterhead-logo">
                <img src="{{ public_path('images/logo-pln.png') }}" alt="Logo PLN">
            </div>
            <div class="letterhead-text">
                <div class="org-name">PT PLN (PERSERO)</div>
                <div class="org-unit">Unit Layanan Pelanggan Cilacap</div>
                <div class="org-address">
                    Jl. Brigjend. Katamso No.52, Sidanegara, Kec. Cilacap Tengah<br>
                    Kabupaten Cilacap, Jawa Tengah 53223
                </div>
            </div>
            <div class="letterhead-right">
                <div class="system-name">{{ config('app.name') }}</div>
                <div class="system-sub">Sistem Inventaris Barang</div>
            </div>
        </div>
    </div>

    {{-- ============ DOC TITLE ============ --}}
    <div class="doc-title">
        <h1>Kartu Inventaris Barang</h1>
        <div class="doc-subtitle">Dicetak pada: {{ $date }}</div>
    </div>
    <hr class="doc-divider">

    {{-- ============ HERO: kode, nama, foto ============ --}}
    <div class="kib-hero">
        <div class="kib-hero-info">
            <div class="kib-hero-code">{{ $commodity->item_code }}</div>
            <div class="kib-hero-name">{{ $commodity->name }}</div>
            <div class="kib-hero-sub">
                {{ $commodity->category->name ?? 'Tanpa Kategori' }} &bull;
                {{ $commodity->location->name ?? 'Tanpa Lokasi' }}
            </div>
        </div>
        <div class="kib-hero-photo">
            @php
                $primaryImage = $commodity->images->where('is_primary', true)->first()
                             ?? $commodity->images->first();
                $photoPath = null;
                if ($primaryImage && !empty($primaryImage->image_path)) {
                    $fullPath = storage_path('app/public/' . $primaryImage->image_path);
                    if (file_exists($fullPath)) {
                        $photoPath = $fullPath;
                    }
                }
            @endphp
            @if($photoPath)
                <img src="{{ $photoPath }}" alt="Foto {{ $commodity->name }}">
            @else
                <div class="no-photo">Foto barang<br>tidak tersedia</div>
            @endif
        </div>
    </div>

    {{-- ============ IDENTITAS BARANG ============ --}}
    <div class="section-title">Identitas Barang</div>
    <table class="info">
        <tr>
            <td class="label">Merk/Brand</td>
            <td class="value">{{ $commodity->brand ?? '-' }}</td>
            <td class="label">Model/Tipe</td>
            <td class="value">{{ $commodity->model ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Serial Number</td>
            <td class="value" colspan="3" style="font-family: monospace;">{{ $commodity->serial_number ?? '-' }}</td>
        </tr>
    </table>

    {{-- ============ DETAIL BARANG ============ --}}
    <div class="section-title">Detail Barang</div>
    <table class="info">
        <tr>
            <td class="label">Tahun Perolehan</td>
            <td class="value">{{ $commodity->purchase_year ?? '-' }}</td>
            <td class="label">Jumlah</td>
            <td class="value">{{ $commodity->quantity }} unit</td>
        </tr>
        <tr>
            <td class="label">Kondisi</td>
            <td class="value">
                @if($commodity->condition === 'baik')
                    <span class="badge badge-success">Baik</span>
                @elseif($commodity->condition === 'rusak_ringan')
                    <span class="badge badge-warning">Rusak Ringan</span>
                @else
                    <span class="badge badge-danger">Rusak Berat</span>
                @endif
            </td>
            <td class="label">Lokasi Detail</td>
            <td class="value">
                @php
                    $locParts = [];
                    if ($commodity->location) {
                        if ($commodity->location->building) $locParts[] = $commodity->location->building;
                        if ($commodity->location->floor) $locParts[] = 'Lt.' . $commodity->location->floor;
                        if ($commodity->location->room) $locParts[] = $commodity->location->room;
                    }
                @endphp
                {{ count($locParts) ? implode(' ', $locParts) : '-' }}
            </td>
        </tr>
    </table>

    {{-- ============ SPESIFIKASI ============ --}}
    @if($commodity->specifications)
    <div class="section-title">Spesifikasi Teknis</div>
    <div class="note-block">{{ $commodity->specifications }}</div>
    @endif

    {{-- ============ CATATAN ============ --}}
    @if($commodity->notes)
    <div class="section-title">Catatan</div>
    <div class="note-block">{{ $commodity->notes }}</div>
    @endif

    {{-- ============ INFORMASI SISTEM ============ --}}
    <div class="section-title">Informasi Sistem</div>
    <table class="info">
        <tr>
            <td class="label">Dibuat oleh</td>
            <td class="value">{{ $commodity->creator->name ?? '-' }}</td>
            <td class="label">Tanggal Pembuatan</td>
            <td class="value">{{ $commodity->created_at->format('d M Y, H:i') }}</td>
        </tr>
        @if($commodity->updater)
        <tr>
            <td class="label">Terakhir diubah</td>
            <td class="value">{{ $commodity->updater->name }}</td>
            <td class="label">Tanggal Update</td>
            <td class="value">{{ $commodity->updated_at->format('d M Y, H:i') }}</td>
        </tr>
        @endif
    </table>

    {{-- ============ RIWAYAT MAINTENANCE ============ --}}
    @if($commodity->maintenances && $commodity->maintenances->count() > 0)
    <div class="section-title">Riwayat Pemeliharaan</div>
    <table class="maintenance">
        <thead>
            <tr>
                <th width="85">Tanggal</th>
                <th>Jenis Pemeliharaan</th>
                <th width="110">Teknisi</th>
                <th width="90" class="text-right">Biaya (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commodity->maintenances->sortByDesc('maintenance_date') as $maintenance)
            <tr>
                <td>{{ $maintenance->maintenance_date->format('d/m/Y') }}</td>
                <td>{{ $maintenance->description }}</td>
                <td>{{ $maintenance->performed_by }}</td>
                <td class="text-right">{{ number_format($maintenance->cost, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total Biaya Maintenance</th>
                <th class="text-right">{{ number_format($commodity->maintenances->sum('cost'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
    @endif

    {{-- ============ SIGNATURE ============ --}}
    <div class="signature-area">
        <div class="signature-right">
            <p>Cilacap, {{ $date }}</p>
            <p style="margin-top: 4px;"><strong>Penanggung Jawab</strong></p>
            <div class="signature-line"></div>
            <p>( _________________________ )</p>
            <p style="margin-top: 3px;">NIP. _______________________</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    {{-- ============ FOOTER ============ --}}
    <div class="page-footer">
        <table>
            <tr>
                <td>{{ config('app.name') }} &bull; PT PLN (Persero) ULP Cilacap &bull; KIB-{{ $commodity->item_code }}</td>
                <td class="text-right">Dokumen ini dicetak secara otomatis oleh sistem pada {{ $date }}</td>
            </tr>
        </table>
    </div>

</body>
</html>