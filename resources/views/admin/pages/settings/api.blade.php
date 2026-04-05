@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-2xl bg-primary-light text-white flex items-center justify-center shadow-xl shadow-blue-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-0.5">API Management</h1>
                <p class="text-slate-500 font-semibold text-sm">Kelola API token untuk integrasi pihak ketiga (Manus, OpenClaw, dll).</p>
            </div>
        </div>
        <a href="{{ url('administrator/settings/api/logs') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            Activity Log
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 p-4 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <p class="text-xs font-bold text-emerald-700">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Token baru - hanya ditampilkan sekali --}}
    @if(session('new_token'))
        <div class="bg-amber-50 border-2 border-amber-300 p-6 rounded-2xl space-y-3">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <h3 class="text-sm font-black text-amber-800">Token Baru: {{ session('token_name') }}</h3>
            </div>
            <p class="text-xs text-amber-700 font-semibold">Salin token ini sekarang! Token <strong>tidak akan ditampilkan lagi</strong> setelah halaman ini ditutup.</p>
            <div class="flex items-center gap-2">
                <input type="text" id="newTokenValue" value="{{ session('new_token') }}" readonly
                    class="flex-1 bg-white border border-amber-300 rounded-xl px-4 py-3 text-xs font-mono text-slate-700 select-all">
                <button type="button" onclick="copyToken()" class="px-4 py-3 bg-amber-500 text-white rounded-xl text-xs font-bold hover:bg-amber-600 transition-all flex-shrink-0">
                    Salin
                </button>
            </div>
        </div>
    @endif

    {{-- API Usage Chart 7 Hari --}}
    @if($recentLogs->count() > 0)
    <div class="glass-card bg-white p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-700 mb-4">API Request (7 Hari Terakhir)</h3>
        <div class="flex items-end gap-1 h-24">
            @php
                $maxReq = $recentLogs->max('total_request') ?: 1;
            @endphp
            @foreach($recentLogs as $log)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <span class="text-[9px] font-bold text-slate-500">{{ $log->total_request }}</span>
                    <div class="w-full bg-blue-500 rounded-t-lg transition-all" style="height: {{ max(4, ($log->total_request / $maxReq) * 80) }}px"></div>
                    <span class="text-[9px] font-medium text-slate-400">{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m') }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Form Buat Token Baru --}}
    <div class="glass-card bg-white p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
            <h3 class="text-lg font-bold text-slate-800">Buat API Token Baru</h3>
        </div>
        <form action="{{ url('administrator/settings/api/store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Token *</label>
                    <input type="text" name="name" required placeholder="Contoh: Manus AI Integration"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    @error('name') <p class="text-xs text-red-500 px-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Rate Limit (per menit)</label>
                    <input type="number" name="rate_limit" value="100" min="1" max="10000"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">IP Whitelist (opsional)</label>
                    <input type="text" name="ip_whitelist" placeholder="Contoh: 103.28.12.1, 159.65.130.50"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    <p class="text-[10px] text-slate-400 px-1">Kosongkan untuk izinkan semua IP. Pisahkan dengan koma.</p>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Masa Berlaku (opsional)</label>
                    <input type="date" name="expires_at"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/10 transition-all">
                    <p class="text-[10px] text-slate-400 px-1">Kosongkan untuk token tanpa expiry.</p>
                </div>
            </div>

            {{-- Permissions --}}
            <div class="space-y-3">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Permissions *</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($permissions as $key => $label)
                        <label class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200 hover:border-blue-300 transition-all cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="text-xs font-bold text-slate-700 block">{{ $label }}</span>
                                <span class="text-[10px] text-slate-400 font-mono">{{ $key }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('permissions') <p class="text-xs text-red-500 px-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                Generate Token
            </button>
        </form>
    </div>

    {{-- Daftar Token --}}
    <div class="glass-card bg-white p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <svg class="w-5 h-5 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"></path></svg>
            <h3 class="text-lg font-bold text-slate-800">API Tokens</h3>
        </div>

        @if($tokens->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                <p class="text-sm font-semibold text-slate-400">Belum ada API token.</p>
                <p class="text-xs text-slate-400">Buat token pertama untuk mulai integrasi.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($tokens as $token)
                    <div class="border border-slate-200 rounded-2xl p-5 hover:border-blue-200 transition-all {{ !$token->aktif ? 'opacity-50' : '' }}">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="space-y-2">
                                <div class="flex items-center gap-3">
                                    <h4 class="text-sm font-bold text-slate-800">{{ $token->name }}</h4>
                                    @if($token->aktif)
                                        @if($token->expires_at && $token->expires_at->isPast())
                                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-[10px] font-bold">Expired</span>
                                        @else
                                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold">Aktif</span>
                                        @endif
                                    @else
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded-full text-[10px] font-bold">Nonaktif</span>
                                    @endif
                                </div>
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach(($token->permissions ?? []) as $perm)
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-bold font-mono">{{ $perm }}</span>
                                    @endforeach
                                </div>
                                <div class="flex items-center gap-4 text-[10px] text-slate-400 font-medium">
                                    <span>Dibuat: {{ $token->created_at->format('d M Y H:i') }}</span>
                                    @if($token->last_used_at)
                                        <span>Terakhir: {{ $token->last_used_at->diffForHumans() }}</span>
                                    @else
                                        <span>Belum pernah digunakan</span>
                                    @endif
                                    @if($token->expires_at)
                                        <span>Expire: {{ $token->expires_at->format('d M Y') }}</span>
                                    @endif
                                    @if($token->ip_whitelist)
                                        <span>IP: {{ $token->ip_whitelist }}</span>
                                    @endif
                                    <span>Rate: {{ $token->rate_limit }}/min</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button type="button" onclick="openEditModal({{ $token->id_api_token }})" class="px-3 py-2 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition-all">
                                    Edit
                                </button>
                                <form action="{{ url('administrator/settings/api/toggle/' . $token->id_api_token) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-2 {{ $token->aktif ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }} rounded-lg text-xs font-bold hover:opacity-80 transition-all">
                                        {{ $token->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                <form action="{{ url('administrator/settings/api/delete/' . $token->id_api_token) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus token ini? Semua integrasi yang menggunakan token ini akan berhenti berfungsi.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-100 text-red-600 rounded-lg text-xs font-bold hover:bg-red-200 transition-all">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Dokumentasi API --}}
    <div class="glass-card bg-white p-8 shadow-sm">
        <div class="flex items-center gap-3 mb-6">
            <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path></svg>
            <h3 class="text-lg font-bold text-slate-800">Dokumentasi API</h3>
        </div>
        <div class="space-y-4">
            <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                <h4 class="text-xs font-black text-slate-600 uppercase tracking-wider">Autentikasi</h4>
                <p class="text-xs text-slate-600">Sertakan token di header setiap request:</p>
                <code class="block bg-slate-800 text-green-400 rounded-lg px-4 py-3 text-xs font-mono">Authorization: Bearer {your_api_token}</code>
            </div>

            <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                <h4 class="text-xs font-black text-slate-600 uppercase tracking-wider">Base URL</h4>
                <code class="block bg-slate-800 text-green-400 rounded-lg px-4 py-3 text-xs font-mono">{{ url('/api/v1') }}</code>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="text-left border-b border-slate-200">
                            <th class="pb-3 font-black text-slate-400 uppercase tracking-wider text-[10px] pr-4">Permission</th>
                            <th class="pb-3 font-black text-slate-400 uppercase tracking-wider text-[10px] pr-4">Method</th>
                            <th class="pb-3 font-black text-slate-400 uppercase tracking-wider text-[10px] pr-4">Endpoint</th>
                            <th class="pb-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $endpoints = [
                                ['read:punia', 'GET', '/punia', 'List data punia'],
                                ['read:punia', 'GET', '/punia/summary', 'Ringkasan punia per tahun'],
                                ['read:punia', 'GET', '/punia/kategori', 'Kategori punia + alokasi'],
                                ['read:punia', 'GET', '/punia/alokasi', 'Data alokasi dana'],
                                ['read:punia', 'GET', '/punia/pendatang', 'Punia dari pendatang'],
                                ['read:krama-tamiu', 'GET', '/krama-tamiu', 'List krama tamiu'],
                                ['read:krama-tamiu', 'GET', '/krama-tamiu/count', 'Statistik jumlah'],
                                ['read:krama-tamiu', 'GET', '/krama-tamiu/{id}', 'Detail pendatang'],
                                ['read:krama-tamiu', 'GET', '/krama-tamiu/belum-punia', 'Yang belum bayar'],
                                ['read:krama-tamiu', 'GET', '/krama-tamiu/acara-punia', 'Acara punia'],
                                ['read:usaha', 'GET', '/usaha', 'List unit usaha'],
                                ['read:usaha', 'GET', '/usaha/{id}', 'Detail usaha + statistik'],
                                ['read:usaha', 'GET', '/usaha/kategori', 'Kategori usaha'],
                                ['read:usaha', 'GET', '/usaha/belum-punia', 'Belum bayar punia'],
                                ['read:donasi', 'GET', '/donasi/program', 'Program donasi + progres'],
                                ['read:donasi', 'GET', '/donasi/program/{id}', 'Detail program + donatur'],
                                ['read:donasi', 'GET', '/donasi/summary', 'Ringkasan donasi'],
                                ['read:donasi', 'GET', '/donasi/kategori', 'Kategori donasi'],
                                ['read:tiket', 'GET', '/tiket/objek-wisata', 'List objek wisata'],
                                ['read:tiket', 'GET', '/tiket/objek-wisata/{id}', 'Detail objek'],
                                ['read:tiket', 'GET', '/tiket/penjualan', 'Data penjualan tiket'],
                                ['read:tiket', 'GET', '/tiket/summary', 'Ringkasan penjualan'],
                                ['read:tiket', 'GET', '/tiket/ketersediaan/{id}', 'Cek ketersediaan'],
                                ['read:keuangan', 'GET', '/keuangan/ringkasan', 'Ringkasan keuangan'],
                                ['read:keuangan', 'GET', '/keuangan/pemasukan', 'Rincian pemasukan'],
                            ];
                        @endphp
                        @foreach($endpoints as $ep)
                            <tr class="hover:bg-slate-50">
                                <td class="py-2 pr-4"><span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded font-mono text-[10px] font-bold">{{ $ep[0] }}</span></td>
                                <td class="py-2 pr-4"><span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded font-bold text-[10px]">{{ $ep[1] }}</span></td>
                                <td class="py-2 pr-4 font-mono text-slate-700 font-medium">{{ $ep[2] }}</td>
                                <td class="py-2 text-slate-500">{{ $ep[3] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                <h4 class="text-xs font-black text-slate-600 uppercase tracking-wider">Contoh Response</h4>
                <pre class="bg-slate-800 text-green-400 rounded-lg px-4 py-3 text-xs font-mono overflow-x-auto">{
  "success": true,
  "message": "Berhasil",
  "data": [ ... ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 150,
    "last_page": 8
  }
}</pre>
            </div>

            <div class="bg-blue-50 rounded-xl p-4 space-y-2">
                <h4 class="text-xs font-black text-blue-600 uppercase tracking-wider">Contoh Penggunaan (cURL)</h4>
                <pre class="bg-slate-800 text-green-400 rounded-lg px-4 py-3 text-xs font-mono overflow-x-auto">curl -X GET "{{ url('/api/v1/punia/summary?tahun=2026') }}" \
  -H "Authorization: Bearer {your_api_token}" \
  -H "Accept: application/json"</pre>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
@foreach($tokens as $token)
<div id="editModal{{ $token->id_api_token }}" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/50" onclick="closeEditModal({{ $token->id_api_token }})"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 relative">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Edit Token: {{ $token->name }}</h3>
            <form action="{{ url('administrator/settings/api/update/' . $token->id_api_token) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Token</label>
                    <input type="text" name="name" value="{{ $token->name }}" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rate Limit</label>
                    <input type="number" name="rate_limit" value="{{ $token->rate_limit }}" min="1" max="10000"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">IP Whitelist</label>
                    <input type="text" name="ip_whitelist" value="{{ $token->ip_whitelist }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Masa Berlaku</label>
                    <input type="date" name="expires_at" value="{{ $token->expires_at?->format('Y-m-d') }}"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Permissions</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($permissions as $key => $label)
                            <label class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}"
                                    {{ in_array($key, $token->permissions ?? []) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 text-blue-600">
                                <span class="text-xs font-bold text-slate-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all">Simpan</button>
                    <button type="button" onclick="closeEditModal({{ $token->id_api_token }})" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
function copyToken() {
    const input = document.getElementById('newTokenValue');
    input.select();
    input.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(input.value);
    event.target.textContent = 'Tersalin!';
    setTimeout(() => { event.target.textContent = 'Salin'; }, 2000);
}

function openEditModal(id) {
    document.getElementById('editModal' + id).classList.remove('hidden');
}

function closeEditModal(id) {
    document.getElementById('editModal' + id).classList.add('hidden');
}
</script>
@endsection
