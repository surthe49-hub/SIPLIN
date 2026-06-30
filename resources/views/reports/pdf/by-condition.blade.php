<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; line-height: 1.4; color: #333; }

        .letterhead { border-bottom: 3px solid #003399; padding-bottom: 12px; margin-bottom: 16px; }
        .letterhead-inner { display: table; width: 100%; }
        .letterhead-logo { display: table-cell; width: 80px; vertical-align: middle; }
        .letterhead-logo img { width: 64px; height: auto; }
        .letterhead-text { display: table-cell; vertical-align: middle; padding-left: 12px; }
        .letterhead-text .org-name { font-size: 13pt; font-weight: bold; color: #003399; letter-spacing: 0.5px; }
        .letterhead-text .org-unit { font-size: 10pt; font-weight: bold; color: #333; margin-top: 2px; }
        .letterhead-text .org-address { font-size: 8pt; color: #555; margin-top: 3px; }
        .letterhead-right { display: table-cell; vertical-align: middle; text-align: right; width: 140px; }
        .letterhead-right .system-name { font-size: 9pt; color: #003399; font-weight: bold; }
        .letterhead-right .system-sub { font-size: 8pt; color: #666; }

        .doc-title { text-align: center; margin-bottom: 14px; }
        .doc-title h1 { font-size: 14pt; font-weight: bold; color: #003399; text-transform: uppercase; letter-spacing: 1px; }
        .doc-title .doc-subtitle { font-size: 9pt; color: #555; margin-top: 4px; }
        .doc-divider { border: none; border-top: 1px solid #ccc; margin: 8px 0 14px 0; }

        /* CONDITION CARDS */
        .condition-cards { display: table; width: 100%; margin-bottom: 16px; border-spacing: 8px 0; }
        .condition-card { display: table-cell; padding: 12px; border: 1px solid #d0d8f0; text-align: center; width: 33%; }
        .condition-card.baik { background-color: #d4edda; border-color: #c3e6cb; }
        .condition-card.ringan { background-color: #fff3cd; border-color: #ffeaa7; }
        .condition-card.berat { background-color: #f8d7da; border-color: #f5c6cb; }
        .condition-card .count { font-size: 22pt; font-weight: bold; }
        .condition-card.baik .count { color: #155724; }
        .condition-card.ringan .count { color: #856404; }
        .condition-card.berat .count { color: #721c24; }
        .condition-card .label { font-size: 9pt; margin-top: 3px; font-weight: bold; }

        /* SECTION TITLE */
        .section-title { font-size: 10pt; font-weight: bold; color: white; padding: 6px 10px; margin-bottom: 0; margin-top: 16px; text-transform: uppercase; letter-spacing: 0.5px; }
        .section-title.baik { background-color: #28a745; }
        .section-title.ringan { background-color: #ffc107; color: #333; }
        .section-title.berat { background-color: #dc3545; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 9pt; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 5px 7px; text-align: left; }
        table.data th { background-color: #f5f8ff; color: #003399; font-weight: bold; font-size: 9pt; text-align: center; }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        .empty-row { color: #999; font-style: italic; text-align: center; padding: 12px; }

        .signature-area { margin-top: 40px; font-size: 9pt; page-break-inside: avoid; }
        .signature-right { text-align: center; width: 240px; float: right; }
        .signature-line { border-bottom: 1px solid #333; margin-top: 55px; margin-bottom: 5px; }

        .page-footer { margin-top: 30px; padding-top: 8px; border-top: 1px solid #ccc; font-size: 7.5pt; color: #888; }
        .page-footer table { width: 100%; }
    </style>
</head>
<body>

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

    <div class="doc-title">
        <h1>{{ $title }}</h1>
        <div class="doc-subtitle">Dicetak pada: {{ $date }}</div>
    </div>
    <hr class="doc-divider">

    {{-- CONDITION SUMMARY CARDS --}}
    <div class="condition-cards">
        <div class="condition-card baik">
            <div class="count">{{ $conditionStats['baik'] }}</div>
            <div class="label">Kondisi Baik</div>
        </div>
        <div class="condition-card ringan">
            <div class="count">{{ $conditionStats['rusak_ringan'] }}</div>
            <div class="label">Rusak Ringan</div>
        </div>
        <div class="condition-card berat">
            <div class="count">{{ $conditionStats['rusak_berat'] }}</div>
            <div class="label">Rusak Berat</div>
        </div>
    </div>

    {{-- DETAIL PER KONDISI --}}
    @foreach(['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'] as $condition => $label)
    @php $cls = $condition === 'baik' ? 'baik' : ($condition === 'rusak_ringan' ? 'ringan' : 'berat'); @endphp
    <div class="section-title {{ $cls }}">Daftar Barang Kondisi: {{ strtoupper($label) }} ({{ $conditionStats[$condition] }} item)</div>
    <table class="data">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="100">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="120">Kategori</th>
                <th width="110">Lokasi</th>
                <th width="100" class="text-right">Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($commodities[$condition]) && $commodities[$condition]->count() > 0)
                @foreach($commodities[$condition] as $index => $commodity)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-family: monospace; font-size: 8pt;">{{ $commodity->item_code }}</td>
                    <td>{{ $commodity->name }}</td>
                    <td>{{ $commodity->category->name ?? '-' }}</td>
                    <td>{{ $commodity->location->name ?? '-' }}</td>
                    <td class="text-right">{{ number_format($commodity->purchase_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="empty-row">Tidak ada barang dengan kondisi {{ $label }}</td>
                </tr>
            @endif
        </tbody>
    </table>
    @endforeach

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