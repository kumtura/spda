@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-2xl bg-green-500 text-white flex items-center justify-center shadow-xl shadow-green-100">
                <i class="bi bi-whatsapp text-3xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-0.5">WAHA WhatsApp API</h1>
                <p class="text-slate-500 font-semibold text-sm">Konfigurasi WAHA API untuk pengiriman e-ticket otomatis via WhatsApp.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
            <p class="text-xs font-bold text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    <!-- WAHA Settings Form -->
    <div class="glass-card bg-white p-8 shadow-sm max-w-2xl">
        <form action="{{ url('administrator/settings/update_waha') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1.5">WAHA API URL</label>
                    <input type="url" name="waha_url" value="{{ $village['waha_url'] ?? '' }}" placeholder="https://your-waha-server.com"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                    <p class="text-[9px] text-slate-400 px-1 mt-1">URL server WAHA Anda (contoh: https://waha.example.com)</p>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1.5">API Key / Token</label>
                    <input type="text" name="waha_token" value="{{ $village['waha_token'] ?? '' }}" placeholder="your-api-key"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all font-mono">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest px-1 block mb-1.5">Session Name</label>
                    <input type="text" name="waha_session" value="{{ $village['waha_session'] ?? 'default' }}" placeholder="default"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-primary-light/20 transition-all">
                    <p class="text-[9px] text-slate-400 px-1 mt-1">Nama session WAHA (default: "default")</p>
                </div>

                <div class="flex items-center gap-3 px-1 pt-2">
                    <input type="checkbox" name="waha_enabled" value="1" {{ ($village['waha_enabled'] ?? false) ? 'checked' : '' }}
                           class="h-4 w-4 rounded text-primary-light border-slate-300 focus:ring-primary-light/20">
                    <label class="text-xs font-bold text-slate-700">Aktifkan pengiriman WhatsApp otomatis</label>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-primary-light hover:bg-primary-dark text-white px-6 py-3 rounded-xl font-bold text-xs shadow-md shadow-blue-500/20 transition-colors flex items-center gap-2">
                    <i class="bi bi-save"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="glass-card bg-white p-6 shadow-sm max-w-2xl border border-slate-100">
        <h3 class="text-xs font-bold text-slate-700 mb-3 flex items-center gap-2">
            <i class="bi bi-info-circle text-primary-light"></i> Tentang WAHA
        </h3>
        <div class="space-y-2 text-[11px] text-slate-500 leading-relaxed">
            <p>WAHA (WhatsApp HTTP API) memungkinkan pengiriman pesan WhatsApp otomatis tanpa WhatsApp Business API resmi.</p>
            <p>Setelah konfigurasi aktif, e-ticket akan dikirim otomatis ke nomor WhatsApp pengunjung setelah pembayaran berhasil. E-ticket berisi kode QR/barcode dan ID tiket yang bisa digunakan untuk masuk ke objek wisata.</p>
            <p class="text-[10px] font-bold text-slate-400 pt-1">Pastikan server WAHA Anda berjalan dan session WhatsApp sudah terhubung.</p>
        </div>
    </div>
</div>
@endsection
