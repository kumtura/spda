<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Punia - {{ $usaha->nama_usaha }} - Tahun {{ $year }}</title>
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
        table tbody tr.unpaid {
            background: #fff5f5;
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
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: 600;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        .badge-warning {
            background: #fff3cd;
            color: #856404;
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
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ strtoupper($village['name'] ?? 'SPDA') }}</h1>
        <h2>Kartu Punia Unit Usaha</h2>
        <p>Tahun {{ $year }}</p>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <p><strong>Nama Usaha:</strong> {{ $usaha->nama_usaha }}</p>
        <p><strong>Banjar:</strong> {{ $usaha->nama_banjar ?? '-' }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WITA</p>
    </div>

    <!-- Kartu Punia Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Bulan</th>
                <th style="width: 25%;" class="text-center">Nominal (Rp)</th>
                <th style="width: 20%;" class="text-center">Tanggal Bayar</th>
                <th style="width: 25%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPaid = 0;
                $paidCount = 0;
            @endphp
            @foreach($months as $monthNum => $monthName)
            @php
                $payment = $payments[$monthNum] ?? null;
                $isPaid = $payment !== null;
                $isPastMonth = $year < $currentYear || ($year == $currentYear && $monthNum < (int)$currentMonth);
                
                if($isPaid) {
                    $totalPaid += $payment->jumlah_dana;
                    $paidCount++;
                }
            @endphp
            <tr class="{{ !$isPaid && $isPastMonth ? 'unpaid' : '' }}">
                <td class="text-center">{{ $monthNum }}</td>
                <td>{{ $monthName }}</td>
                <td class="text-right">
                    @if($isPaid)
                        {{ number_format($payment->jumlah_dana, 0, ',', '.') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($isPaid)
                        {{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->translatedFormat('d M Y') }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @if($isPaid)
                        <span class="badge badge-success">Lunas</span>
                    @elseif($isPastMonth)
                        <span class="badge badge-danger">Terlewat</span>
                    @else
                        <span class="badge badge-warning">Belum Bayar</span>
                    @endif
                </td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" class="text-right">TOTAL TERBAYAR ({{ $paidCount }} bulan):</td>
                <td class="text-right">{{ number_format($totalPaid, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <div class="summary-row">
            <span class="summary-label">Total Bulan Terbayar:</span>
            <span class="summary-value">{{ $paidCount }} dari 12 bulan</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Kontribusi Tahun {{ $year }}:</span>
            <span class="summary-value">Rp {{ number_format($totalPaid, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Punia Desa Adat (SPDA)</p>
        <p>{{ $village['name'] ?? 'SPDA' }}@if(isset($village['address'])) - {{ $village['address'] }}@endif</p>
        <p>Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WITA</p>
    </div>
</body>
</html>
