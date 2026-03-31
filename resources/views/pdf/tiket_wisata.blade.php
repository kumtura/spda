<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Tiket Wisata - {{ $tiket->kode_tiket }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 20px;
            background: #f8f9fa;
        }
        .ticket {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #00a6eb 0%, #0090d0 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            opacity: 0.9;
        }
        .qr-section {
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-bottom: 2px dashed #dee2e6;
        }
        .qr-section img {
            width: 200px;
            height: 200px;
            border: 3px solid white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .qr-code {
            font-size: 14px;
            font-weight: bold;
            margin-top: 15px;
            color: #495057;
        }
        .details {
            padding: 30px;
        }
        .detail-row {
            margin-bottom: 20px;
        }
        .detail-label {
            font-size: 11px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding-bottom: 5px;
            display: block;
        }
        .detail-value {
            font-size: 14px;
            font-weight: bold;
            color: #212529;
            display: block;
        }
        .detail-grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .detail-col {
            width: 48%;
            display: inline-block;
            vertical-align: top;
            padding-right: 15px;
        }
        .categories {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .categories h3 {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }
        .category-item {
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .category-item:last-child {
            border-bottom: none;
        }
        .category-name {
            font-size: 13px;
            color: #495057;
            display: inline-block;
            width: 70%;
        }
        .category-qty {
            text-align: right;
            font-weight: bold;
            color: #212529;
            display: inline-block;
            width: 28%;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 2px dashed #dee2e6;
        }
        .footer-note {
            font-size: 10px;
            color: #6c757d;
            line-height: 1.6;
        }
        .footer-note strong {
            color: #495057;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>{{ $village['name'] ?? 'SPDA' }}</h1>
            <p>E-Tiket Objek Wisata</p>
        </div>

        <div class="qr-section">
            @if(isset($qrCodeBase64) && $qrCodeBase64)
                <img src="{{ $qrCodeBase64 }}" alt="QR Code" style="width: 200px; height: 200px;">
            @else
                <div style="width: 200px; height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                    <span style="color: #999; font-size: 12px;">QR Code</span>
                </div>
            @endif
            <div class="qr-code">{{ $tiket->kode_tiket }}</div>
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="detail-label">Objek Wisata</span>
                <span class="detail-value">{{ $tiket->objekWisata->nama_objek }}</span>
            </div>

            <div class="detail-grid">
                <div class="detail-col">
                    <span class="detail-label">Tanggal Kunjungan</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($tiket->tanggal_kunjungan)->translatedFormat('d M Y') }}</span>
                </div>
                <div class="detail-col">
                    <span class="detail-label">Total Pembayaran</span>
                    <span class="detail-value" style="color: #10b981;">Rp {{ number_format($tiket->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="categories">
                <h3>Detail Tiket</h3>
                @foreach($tiket->details as $detail)
                <div class="category-item">
                    <span class="category-name">{{ $detail->kategoriTiket->nama_kategori }}</span>
                    <span class="category-qty">{{ $detail->jumlah }}x</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="footer">
            <div class="footer-note">
                <strong>Informasi Penting:</strong><br>
                • Tunjukkan QR code ini saat memasuki objek wisata<br>
                • Tiket berlaku untuk tanggal yang tertera<br>
                • Simpan tiket ini dengan baik<br>
                • Hubungi pengelola jika ada kendala
            </div>
        </div>
    </div>
</body>
</html>
