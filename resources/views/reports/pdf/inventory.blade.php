<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
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

        /* ============ DOCUMENT TITLE ============ */
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
            margin: 8px 0 14px 0;
        }

        /* ============ META INFO ============ */
        .meta {
            margin-bottom: 14px;
            font-size: 9pt;
            background-color: #f5f8ff;
            border: 1px solid #d0d8f0;
            padding: 8px 12px;
        }
        .meta table {
            width: 100%;
        }
        .meta td {
            padding: 2px 0;
        }
        .meta .meta-label {
            font-weight: bold;
            width: 120px;
            color: #003399;
        }

        /* ============ FILTER INFO ============ */
        .filter-info {
            font-size: 8pt;
            color: #555;
            margin-bottom: 12px;
            padding: 5px 8px;
            background-color: #fffde7;
            border-left: 3px solid #f9a825;
        }

        /* ============ DATA TABLE ============ */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        table.data th,
        table.data td {
            border: 1px solid #ccc;
            padding: 5px 7px;
            text-align: left;
        }
        table.data th {
            background-color: #003399;
            color: #ffffff;
            font-weight: bold;
            font-size: 9pt;
            text-align: center;
        }
        table.data td {
            font-size: 8.5pt;
        }
        table.data tr:nth-child(even) {
            background-color: #f2f5ff;
        }
        table.data tfoot tr {
            background-color: #e8ecf8;
        }
        table.data tfoot th {
            background-color: #003399;
            color: #ffffff;
            font-size: 9pt;
        }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        /* ============ BADGE ============ */
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-success { background-color: #d4edda; color: #155724; }
        .badge-warning { background-color: #fff3cd; color: #856404; }
        .badge-danger  { background-color: #f8d7da; color: #721c24; }

        /* ============ SUMMARY ============ */
        .summary {
            margin-top: 16px;
            padding: 10px 14px;
            background-color: #f5f8ff;
            border: 1px solid #d0d8f0;
            font-size: 9pt;
        }
        .summary-title {
            font-weight: bold;
            color: #003399;
            margin-bottom: 6px;
            font-size: 9pt;
        }
        .summary table {
            width: 50%;
        }
        .summary td {
            padding: 2px 0;
        }
        .summary .summary-label {
            font-weight: bold;
            width: 120px;
        }

        /* ============ SIGNATURE ============ */
        .signature-area {
            margin-top: 40px;
            font-size: 9pt;
        }
        .signature-right {
            text-align: center;
            width: 220px;
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

        .page-break { page-break-after: always; }
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

    {{-- ============ DOCUMENT TITLE ============ --}}
    <div class="doc-title">
        <h1>{{ $title }}</h1>
        <div class="doc-subtitle">Dicetak pada: {{ $date }}</div>
    </div>
    <hr class="doc-divider">

    {{-- ============ FILTER INFO (jika ada) ============ --}}
    @if(!empty($filters) && array_filter($filters))
    <div class="filter-info">
        <strong>Filter aktif:</strong>
        @if(!empty($filters['category_id']))
            Kategori: {{ \App\Models\Category::find($filters['category_id'])?->name ?? $filters['category_id'] }}&nbsp;&nbsp;
        @endif
        @if(!empty($filters['location_id']))
            Lokasi: {{ \App\Models\Location::find($filters['location_id'])?->name ?? $filters['location_id'] }}&nbsp;&nbsp;
        @endif
        @if(!empty($filters['condition']))
            Kondisi: {{ ucfirst(str_replace('_', ' ', $filters['condition'])) }}&nbsp;&nbsp;
        @endif
        @if(!empty($filters['year']))
            Tahun: {{ $filters['year'] }}&nbsp;&nbsp;
        @endif
    </div>
    @endif

    {{-- ============ META SUMMARY ============ --}}
    <div class="meta">
        <table>
            <tr>
                <td class="meta-label">Total Barang</td>
                <td>: {{ number_format($commodities->count()) }} item</td>
                <td width="30"></td>
                <td class="meta-label" width="130">Kondisi Baik</td>
                <td>: {{ $commodities->where('condition', 'baik')->count() }} item</td>
            </tr>
            <tr>
                <td class="meta-label">Total Nilai</td>
                <td>: Rp {{ number_format($commodities->sum('purchase_price'), 0, ',', '.') }}</td>
                <td></td>
                <td class="meta-label">Rusak Ringan</td>
                <td>: {{ $commodities->where('condition', 'rusak_ringan')->count() }} item</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td class="meta-label">Rusak Berat</td>
                <td>: {{ $commodities->where('condition', 'rusak_berat')->count() }} item</td>
            </tr>
        </table>
    </div>

    {{-- ============ DATA TABLE ============ --}}
    <table class="data">
        <thead>
            <tr>
                <th width="28" class="text-center">No</th>
                <th width="90">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="95">Kategori</th>
                <th width="100">Lokasi</th>
                <th width="65" class="text-center">Kondisi</th>
                <th width="40" class="text-center">Qty</th>
                <th width="90" class="text-right">Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($commodities as $index => $commodity)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="font-family: monospace; font-size: 8pt;">{{ $commodity->item_code }}</td>
                <td>{{ $commodity->name }}</td>
                <td>{{ $commodity->category->name ?? '-' }}</td>
                <td>{{ $commodity->location->name ?? '-' }}</td>
                <td class="text-center">
                    @if($commodity->condition === 'baik')
                        <span class="badge badge-success">Baik</span>
                    @elseif($commodity->condition === 'rusak_ringan')
                        <span class="badge badge-warning">R.Ringan</span>
                    @else
                        <span class="badge badge-danger">R.Berat</span>
                    @endif
                </td>
                <td class="text-center">{{ $commodity->quantity }}</td>
                <td class="text-right">{{ number_format($commodity->purchase_price, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="color: #888; padding: 16px;">
                    Tidak ada data barang sesuai filter yang dipilih.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">Total</th>
                <th class="text-center">{{ $commodities->sum('quantity') }}</th>
                <th class="text-right">{{ number_format($commodities->sum('purchase_price'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

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

    {{-- ============ PAGE FOOTER ============ --}}
    <div class="page-footer">
        <table>
            <tr>
                <td>{{ config('app.name') }} &bull; PT PLN (Persero) ULP Cilacap</td>
                <td class="text-right">Dokumen ini dicetak secara otomatis oleh sistem pada {{ $date }}</td>
            </tr>
        </table>
    </div>

</body>
</html>