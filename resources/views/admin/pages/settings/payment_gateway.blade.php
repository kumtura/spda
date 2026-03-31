@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-2xl bg-primary-light text-white flex items-center justify-center shadow-xl shadow-blue-100">
                <i class="bi bi-credit-card-2-front text-3xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-0.5">Payment Gateway</h1>
                <p class="text-slate-500 font-semibold text-sm">Konfigurasi integrasi pembayaran otomatis menggunakan Xendit.</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3">
            <i class="bi bi-check-circle-fill text-emerald-500 text-xl"></i>
            <p class="text-xs font-bold text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Settings Form -->
    <div class="glass-card bg-white p-8 shadow-sm max-w-2xl">
        <form action="{{ route('administrator.settings.payment_gateway.post') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <img src="https://raw.githubusercontent.com/xendit/xendit-php/master/xendit_logo.png" class="h-6 object-contain" alt="Xendit">
                    <h3 class="text-lg font-bold text-slate-800">Xendit Configuration</h3>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">API Key (Public)</label>
                    <input type="text" name="api_key" value="{{ $setting->api_key ?? '' }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                           placeholder="xnd_public_...">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Secret Key</label>
                    <input type="password" name="secret_key" value="{{ $setting->secret_key ?? '' }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                           placeholder="xnd_development_...">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Webhook Token</label>
                    <input type="text" name="webhook_token" value="{{ $setting->webhook_token ?? '' }}"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all"
                           placeholder="Token verifikasi webhook">
                </div>

                <div class="flex flex-col gap-4 pt-2">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" class="sr-only peer" {{ ($setting->is_active ?? false) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-light"></div>
                        <span class="ms-3 text-sm font-bold text-slate-700">Aktifkan Payment Gateway</span>
                    </label>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_sandbox" class="sr-only peer" {{ ($setting->is_sandbox ?? true) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                        <span class="ms-3 text-sm font-bold text-slate-700">Sandbox / Development Mode</span>
                    </label>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-primary-light text-white py-4 rounded-xl font-black text-sm shadow-lg shadow-blue-100 hover:shadow-xl hover:shadow-blue-200 transition-all active:scale-[0.98]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

        {{-- Info Box --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
            <h4 class="text-sm font-bold text-blue-800 mb-2 flex items-center gap-2">
                <i class="bi bi-info-circle-fill"></i> Panduan Singkat
            </h4>
            <ul class="text-xs text-blue-700/80 space-y-2 list-disc pl-4 font-medium">
                <li>Dapatkan API Key dan Secret Key dari dashboard Xendit (Pengaturan -> API Keys).</li>
                <li>Pastikan Webhook Token sesuai dengan yang diatur di Xendit untuk keamanan transaksi.</li>
                <li>Gunakan mode Development/Sandbox untuk pengujian sebelum beralih ke Production.</li>
            </ul>
        </div>
    {{-- Payment Channels Section --}}
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Metode Pembayaran (Channels)</h3>
                <p class="text-sm text-slate-500 mt-1">Kelola metode pembayaran yang tersedia dan unggah icon kustom.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="pb-4 font-bold text-slate-400 text-[10px] uppercase tracking-widest whitespace-nowrap px-4">Metode</th>
                        <th class="pb-4 font-bold text-slate-400 text-[10px] uppercase tracking-widest whitespace-nowrap px-4">Channel Code</th>
                        <th class="pb-4 font-bold text-slate-400 text-[10px] uppercase tracking-widest whitespace-nowrap px-4">Tipe</th>
                        <th class="pb-4 font-bold text-slate-400 text-[10px] uppercase tracking-widest whitespace-nowrap px-4">Status</th>
                        <th class="pb-4 font-bold text-slate-400 text-[10px] uppercase tracking-widest text-right whitespace-nowrap px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($channels as $channel)
                    <tr>
                        <td class="py-4 whitespace-nowrap px-4">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-12 bg-slate-50 rounded-xl flex items-center justify-center border border-slate-100 overflow-hidden p-1">
                                    @if($channel->icon_url)
                                        <img src="{{ str_starts_with($channel->icon_url, 'http') ? $channel->icon_url : asset($channel->icon_url) }}" class="max-h-full max-w-full object-contain" alt="{{ $channel->name }}">
                                    @else
                                        <i class="bi bi-image text-slate-300"></i>
                                    @endif
                                </div>
                                <span class="text-xs font-bold text-slate-800">{{ $channel->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 whitespace-nowrap px-4">
                            <code class="text-[10px] font-mono bg-slate-50 px-2 py-1 rounded-lg text-slate-600 border border-slate-100">{{ $channel->code }}</code>
                        </td>
                        <td class="py-4 whitespace-nowrap px-4">
                            <span class="text-[10px] font-bold px-2 py-1 rounded-lg {{ $channel->type == 'VA' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ $channel->type }}
                            </span>
                        </td>
                        <td class="py-4 whitespace-nowrap px-4">
                            @if($channel->is_active)
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="py-4 whitespace-nowrap text-right px-4">
                            <button type="button" 
                                    onclick="openEditModal({{ $channel->id_payment_channel }}, '{{ $channel->name }}', {{ $channel->is_active ? 'true' : 'false' }})"
                                    class="h-8 w-8 bg-white border border-slate-200 text-slate-400 rounded-lg hover:text-primary-light hover:border-primary-light transition-all inline-flex items-center justify-center shadow-sm">
                                <i class="bi bi-pencil-square text-sm"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Edit Channel -->
<div id="editChannelModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeEditModal()"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-8 shadow-2xl transition-all">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800">Edit Metode Pembayaran</h3>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600"><i class="bi bi-x-lg"></i></button>
            </div>

            <form id="editChannelForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Nama Tampilan</label>
                        <input type="text" name="name" id="modal_name" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-primary-light/10 transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1 px-1">Unggah Icon Baru (1:1)</label>
                        <input type="file" name="icon" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold focus:outline-none focus:ring-4 focus:ring-primary-light/10 transition-all shadow-sm">
                    </div>

                    <div class="flex items-center justify-between bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <div>
                            <p class="text-xs font-bold text-slate-800">Status Aktif</p>
                            <p class="text-[10px] text-slate-500 font-medium">Tampilkan di halaman publik</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="modal_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" onclick="closeEditModal()" class="flex-1 py-4 px-4 bg-slate-100 text-slate-600 rounded-2xl text-xs font-bold transition-all hover:bg-slate-200">Batal</button>
                    <button type="submit" class="flex-1 py-4 px-4 bg-primary-light text-white rounded-2xl text-xs font-bold shadow-lg shadow-blue-100 transition-all hover:scale-[1.02] active:scale-[0.98]">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(id, name, isActive) {
        const form = document.getElementById('editChannelForm');
        form.action = `/administrator/settings/payment_gateway/channel/${id}`;
        document.getElementById('modal_name').value = name;
        document.getElementById('modal_active').checked = isActive === true;
        document.getElementById('editChannelModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editChannelModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
</div>
@endsection
