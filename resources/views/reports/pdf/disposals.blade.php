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

        .meta { margin-bottom: 14px; font-size: 9pt; background-color: #f5f8ff; border: 1px solid #d0d8f0; padding: 8px 12px; }
        .meta table { width: 100%; }
        .meta td { padding: 2px 0; }
        .meta .meta-label { font-weight: bold; width: 130px; color: #003399; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 9pt; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 5px 7px; text-align: left; }
        table.data th { background-color: #003399; color: #ffffff; font-weight: bold; text-align: center; }
        table.data tr:nth-child(even) { background-color: #f2f5ff; }

        .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 8pt; font-weight: bold; }
        .badge-pending { background-color: #fff3cd; color: #856404; }
        .badge-approved { background-color: #d4edda; color: #155724; }
        .badge-rejected { background-color: #f8d7da; color: #721c24; }

        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }

        .summary { margin-top: 16px; padding: 10px 14px; background-color: #f5f8ff; border: 1px solid #d0d8f0; font-size: 9pt; }
        .summary-title { font-weight: bold; color: #003399; margin-bottom: 6px; }
        .summary table { width: 100%; }
        .summary td { padding: 3px 0; }

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

    <div class="meta">
        <table>
            <tr>
                <td class="meta-label">Total Transaksi</td>
                <td>: {{ $disposals->count() }} transaksi</td>
                <td width="30"></td>
                <td class="meta-label" width="100">Pending</td>
                <td>: {{ $disposals->where('status', 'pending')->count() }}</td>
            </tr>
            <tr>
                <td class="meta-label">Disetujui</td>
                <td>: {{ $disposals->where('status', 'approved')->count() }}</td>
                <td></td>
                <td class="meta-label">Ditolak</td>
                <td>: {{ $disposals->where('status', 'rejected')->count() }}</td>
            </tr>
            <tr>
                <td class="meta-label">Total Nilai Aset</td>
                <td colspan="4">: Rp {{ number_format($disposals->sum(function($d) { return $d->commodity->purchase_price ?? 0; }), 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="85">Kode</th>
                <th>Barang</th>
                <th width="100">Alasan</th>
                <th width="90">Metode</th>
                <th width="100">Pemohon</th>
                <th width="70" class="text-center">Status</th>
                <th width="70" class="text-center">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($disposals as $index => $disposal)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="font-family: monospace; font-size: 8pt;">{{ $disposal->commodity->item_code ?? '-' }}</td>
                <td>{{ $disposal->commodity->name ?? '-' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $disposal->reason)) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $disposal->method)) }}</td>
                <td>{{ $disposal->requester->name ?? '-' }}</td>
                <td class="text-center">
                    @if($disposal->status === 'pending')
                        <span class="badge badge-pending">Pending</span>
                    @elseif($disposal->status === 'approved')
                        <span class="badge badge-approved">Disetujui</span>
                    @else
                        <span class="badge badge-rejected">Ditolak</span>
                    @endif
                </td>
                <td class="text-center">{{ optional($disposal->disposal_date)->format('d/m/Y') ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center" style="color: #999; font-style: italic; padding: 16px;">Tidak ada data penghapusan pada periode ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($disposals->count() > 0)
    <div class="summary">
        <div class="summary-title">Ringkasan Status</div>
        <table>
            <tr>
                <td width="40%">Pending</td>
                <td width="30%">: {{ $disposals->where('status', 'pending')->count() }} item</td>
                <td class="text-right">Rp {{ number_format($disposals->where('status', 'pending')->sum(function($d) { return $d->commodity->purchase_price ?? 0; }), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Disetujui</td>
                <td>: {{ $disposals->where('status', 'approved')->count() }} item</td>
                <td class="text-right">Rp {{ number_format($disposals->where('status', 'approved')->sum(function($d) { return $d->commodity->purchase_price ?? 0; }), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Ditolak</td>
                <td>: {{ $disposals->where('status', 'rejected')->count() }} item</td>
                <td class="text-right">Rp {{ number_format($disposals->where('status', 'rejected')->sum(function($d) { return $d->commodity->purchase_price ?? 0; }), 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    @endif

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