<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Dana Punia - {{ $month_name }} {{ $year }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            padding: 30px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
        }
        .header h1 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .header h2 {
            font-size: 15px;
            color: #34495e;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .header p {
            font-size: 11px;
            color: #7f8c8d;
        }
        .info-box {
            background: #f8f9fa;
            padding: 12px 15px;
            margin-bottom: 25px;
            border-left: 3px solid #2c3e50;
        }
        .info-box p {
            margin: 4px 0;
            font-size: 10px;
            color: #34495e;
        }
        .info-box strong {
            color: #2c3e50;
            font-weight: 600;
        }
        h3 {
            font-size: 12px;
            color: #2c3e50;
            margin: 25px 0 12px 0;
            padding: 8px 0;
            border-bottom: 1px solid #bdc3c7;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border: 1px solid #bdc3c7;
        }
        table thead {
            background: #34495e;
            color: white;
        }
        table th {
            padding: 10px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-right: 1px solid #2c3e50;
        }
        table th:last-child {
            border-right: none;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ecf0f1;
            border-right: 1px solid #ecf0f1;
            font-size: 10px;
            color: #2c3e50;
        }
        table td:last-child {
            border-right: none;
        }
        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        table tbody tr.total-row {
            background: #ecf0f1;
            font-weight: 600;
            border-top: 2px solid #95a5a6;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border: 1px solid #bdc3c7;
        }
        .summary-row {
            padding: 10px 0;
            border-bottom: 1px solid #d5d8dc;
            display: table;
            width: 100%;
        }
        .summary-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 12px;
            padding-top: 15px;
            border-top: 2px solid #2c3e50;
            margin-top: 5px;
        }
        .summary-label {
            font-weight: 600;
            color: #2c3e50;
            display: table-cell;
            width: 70%;
        }
        .summary-value {
            display: table-cell;
            text-align: right;
            color: #34495e;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
            padding-top: 20px;
            border-top: 1px solid #bdc3c7;
        }
        .footer p {
            margin: 3px 0;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #95a5a6;
            font-style: italic;
            background: #f8f9fa;
            border: 1px dashed #bdc3c7;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ strtoupper($village['name'] ?? 'SPDA') }}</h1>
        <h2>Laporan Dana Punia</h2>
        <p>Periode: {{ $month_name }} {{ $year }}</p>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
        <p><strong>Dicetak oleh:</strong> Sistem SPDA</p>
    </div>

    <!-- Pemasukan Section -->
    <h3>Pemasukan Dana Punia</h3>
    @if($pemasukan->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 35%;">Nama Donatur</th>
                <th style="width: 20%;">Kontak</th>
                <th style="width: 25%;" class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemasukan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->translatedFormat('d M Y') }}</td>
                <td>{{ $item->nama_donatur }}</td>
                <td>{{ $item->no_wa ?: '-' }}</td>
                <td class="text-right">{{ number_format($item->jumlah_dana, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL PEMASUKAN:</td>
                <td class="text-right">{{ number_format($total_pemasukan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="no-data">Tidak ada data pemasukan pada periode ini</div>
    @endif

    <!-- Pengeluaran Section -->
    <h3>Pengeluaran Dana Punia</h3>
    @if($pengeluaran->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Kategori</th>
                <th style="width: 30%;">Keperluan</th>
                <th style="width: 25%;" class="text-right">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengeluaran as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_alokasi)->translatedFormat('d M Y') }}</td>
                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $item->judul }}</td>
                <td class="text-right">{{ number_format($item->nominal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL PENGELUARAN:</td>
                <td class="text-right">{{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="no-data">Tidak ada data pengeluaran pada periode ini</div>
    @endif

    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Total Pemasukan:</span>
            <span class="summary-value">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Pengeluaran:</span>
            <span class="summary-value">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Saldo Periode Ini:</span>
            <span class="summary-value">Rp {{ number_format($saldo, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Punia Desa Adat (SPDA)</p>
        <p>{{ $village['name'] ?? 'SPDA' }}@if(isset($village['address'])) - {{ $village['address'] }}@endif</p>
        <p>Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>
</body>
</html>
