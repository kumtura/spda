<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt Punia Pendatang - {{ $pendatang->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            padding: 30px;
            line-height: 1.5;
        }
        .receipt-box {
            max-width: 500px;
            margin: 0 auto;
            border: 2px solid #2c3e50;
            padding: 0;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px 25px;
            text-align: center;
        }
        .header h1 {
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 4px;
        }
        .header h2 {
            font-size: 13px;
            font-weight: 600;
            opacity: 0.9;
            margin-bottom: 3px;
        }
        .header p {
            font-size: 9px;
            opacity: 0.7;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .body-content {
            padding: 25px;
        }
        .receipt-id {
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #bdc3c7;
        }
        .receipt-id strong {
            color: #2c3e50;
            font-size: 11px;
            display: block;
            margin-top: 3px;
        }
        .info-row {
            display: table;
            width: 100%;
            padding: 8px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            display: table-cell;
            width: 40%;
            font-size: 10px;
            color: #7f8c8d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            vertical-align: middle;
        }
        .info-value {
            display: table-cell;
            text-align: right;
            font-size: 11px;
            color: #2c3e50;
            font-weight: 600;
            vertical-align: middle;
        }
        .amount-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 15px 20px;
            margin: 20px 0;
            text-align: center;
        }
        .amount-box .label {
            font-size: 9px;
            color: #7f8c8d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .amount-box .amount {
            font-size: 22px;
            font-weight: bold;
            color: #166534;
        }
        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            font-size: 9px;
            font-weight: 700;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-badge.status-completed {
            background: #d4edda;
            color: #155724;
        }
        .status-badge.status-pending {
            background: #fef3c7;
            color: #92400e;
        }
        .footer {
            text-align: center;
            padding: 15px 25px 20px;
            border-top: 1px dashed #bdc3c7;
        }
        .footer p {
            font-size: 8px;
            color: #95a5a6;
            margin: 2px 0;
        }
        .footer .stamp {
            font-size: 10px;
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    @php
        $receiptNumber = $receiptCode ?? ('TM-' . str_pad($payment->id_punia_pendatang, 6, '0', STR_PAD_LEFT));
        $isCompleted = in_array($payment->status_pembayaran, ['completed', 'lunas'], true) || $payment->status_verifikasi === 'approved';
        $statusText = $isCompleted ? 'Lunas' : 'Menunggu Verifikasi';
        $statusClass = $isCompleted ? 'status-completed' : 'status-pending';
    @endphp
    <div class="receipt-box">
        <div class="header">
            <h1>{{ strtoupper($village['name'] ?? 'SPDA') }}</h1>
            <h2>Bukti Pembayaran Punia</h2>
            <p>Sistem Punia Desa Adat</p>
        </div>

        <div class="body-content">
            <div class="receipt-id">
                No. Referensi
                <strong>#{{ $receiptNumber }}</strong>
            </div>

            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">{{ $pendatang->nama }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Banjar</span>
                <span class="info-value">{{ $pendatang->banjar->nama_banjar_adat ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Periode</span>
                <span class="info-value">{{ $bulanName }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Bayar</span>
                <span class="info-value">{{ $payment->tanggal_bayar ? \Carbon\Carbon::parse($payment->tanggal_bayar)->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode</span>
                <span class="info-value">{{ ucfirst($payment->metode_pembayaran ?? '-') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value"><span class="status-badge {{ $statusClass }}">{{ $statusText }}</span></span>
            </div>

            <div class="amount-box">
                <div class="label">Total Pembayaran</div>
                <div class="amount">Rp {{ number_format($payment->nominal, 0, ',', '.') }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Dicatat Pada</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($payment->updated_at ?? $payment->created_at)->translatedFormat('d F Y, H:i') }} WITA</span>
            </div>
        </div>

        <div class="footer">
            <p class="stamp">{{ $village['name'] ?? 'SPDA' }}</p>
            <p>Dokumen ini digenerate otomatis oleh Sistem Punia Desa Adat (SPDA)</p>
            <p>Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WITA</p>
        </div>
    </div>
</body>
</html>
