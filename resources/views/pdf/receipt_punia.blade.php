<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt Punia - {{ $usaha->nama_usaha }}</title>
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
            background: #d4edda;
            color: #155724;
            padding: 3px 12px;
            font-size: 9px;
            font-weight: 700;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .divider {
            border: none;
            border-top: 1px dashed #bdc3c7;
            margin: 15px 0;
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
    <div class="receipt-box">
        <!-- Header -->
        <div class="header">
            <h1>{{ strtoupper($village['name'] ?? 'SPDA') }}</h1>
            <h2>Bukti Pembayaran Punia</h2>
            <p>Sistem Punia Desa Adat</p>
        </div>

        <!-- Body -->
        <div class="body-content">
            <div class="receipt-id">
                No. Referensi
                <strong>#PN-{{ str_pad($payment->id_dana_punia, 6, '0', STR_PAD_LEFT) }}</strong>
            </div>

            <div class="info-row">
                <span class="info-label">Unit Usaha</span>
                <span class="info-value">{{ $usaha->nama_usaha }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Banjar</span>
                <span class="info-value">{{ $usaha->nama_banjar ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Periode</span>
                <span class="info-value">{{ $bulanName }} {{ $payment->tahun_punia }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Bayar</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode</span>
                <span class="info-value">{{ ucfirst($payment->metode_pembayaran ?? $payment->metode ?? '-') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value"><span class="status-badge">Lunas</span></span>
            </div>

            <div class="amount-box">
                <div class="label">Total Pembayaran</div>
                <div class="amount">Rp {{ number_format($payment->jumlah_dana, 0, ',', '.') }}</div>
            </div>

            <div class="info-row">
                <span class="info-label">Dicatat Pada</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($payment->created_at)->translatedFormat('d F Y, H:i') }} WITA</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="stamp">{{ $village['name'] ?? 'SPDA' }}</p>
            <p>Dokumen ini digenerate otomatis oleh Sistem Punia Desa Adat (SPDA)</p>
            <p>Dicetak pada {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WITA</p>
        </div>
    </div>
</body>
</html>
