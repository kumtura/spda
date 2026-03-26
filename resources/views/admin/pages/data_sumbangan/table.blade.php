@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    showAddModal: false,
    donatorType: '1', // 1: Anonim, 2: Donatur, 3: Investor

    // Filters
    dateAwal: '{{ $_GET['dateawal'] ?? '' }}',
    dateAkhir: '{{ $_GET['dateakhir'] ?? '' }}',

    filter() {
        window.location = '{{ url('administrator/datasumbangan') }}?dateawal=' + this.dateAwal + '&dateakhir=' + this.dateAkhir;
    }
}">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Sumbangan Sukarela</h1>
            <p class="text-slate-500 font-medium text-sm">Kelola dan pantau aliran dana sumbangan dari berbagai donatur.</p>
        </div>
        <button @click="showAddModal = true" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-plus-lg text-lg"></i>
            Tambah Sumbangan
        </button>
    </div>

    <!-- Financial Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="glass-card p-5 h-28 flex flex-col justify-between border border-slate-200">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Anonim</span>
            <p class="text-lg font-black text-slate-800 tracking-tight">Rp {{ format_rupiah($total_anonim) }}</p>
        </div>
        <div class="glass-card p-5 h-28 flex flex-col justify-between border border-slate-200">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Donatur</span>
            <p class="text-lg font-black text-slate-800 tracking-tight">Rp {{ format_rupiah($total_karyawan) }}</p>
        </div>
        <div class="glass-card p-5 h-28 flex flex-col justify-between border border-slate-200">
            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none">Investor</span>
            <p class="text-lg font-black text-slate-800 tracking-tight">Rp {{ format_rupiah($total_usaha) }}</p>
        </div>
        <div class="glass-card p-5 h-28 flex flex-col justify-between bg-primary-light text-white border-none shadow-lg shadow-blue-100">
            <span class="text-[9px] font-black text-white/70 uppercase tracking-widest leading-none">Total Keseluruhan</span>
            <p class="text-lg font-black tracking-tight">Rp {{ format_rupiah($total_all) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col lg:flex-row gap-4 items-end">
            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Rentang Awal</label>
                    <input type="date" x-model="dateAwal" id="dateawal"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Rentang Akhir</label>
                    <input type="date" x-model="dateAkhir" id="dateakhir"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5">
                </div>
            </div>
            <div class="flex gap-2 w-full lg:w-auto">
                <button @click="filter()" 
                        class="flex-1 lg:flex-none h-10 px-6 bg-slate-900 hover:bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-md transition-all">
                    Cari
                </button>
                <button onclick="export_pdf()" 
                        class="flex-1 lg:flex-none h-10 px-6 bg-white border border-slate-200 text-slate-500 hover:text-rose-600 hover:border-rose-100 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-sm transition-all">
                    <i class="bi bi-file-earmark-pdf mr-1"></i> PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse" id="ikantable">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penyumbang</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Catatan (Note)</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($sumbangan as $rows)
                    <tr class="group hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black text-[10px]">
                                    {{ substr($rows->nama ?: 'A', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-xs font-black text-slate-700 tracking-tight leading-none mb-1">{{ $rows->nama ?: 'Anonim' }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $rows->alamat ?: '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-0.5">
                                <p class="text-xs font-black text-emerald-600 tracking-tight">Rp {{ format_rupiah($rows->nominal) }}</p>
                                <span class="text-[8px] font-black uppercase text-slate-400 flex items-center gap-1">
                                    <i class="bi bi-credit-card"></i> Transfer
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[10px] font-medium text-slate-500 max-w-xs italic line-clamp-1">"{{ $rows->deskripsi ?: '-' }}"</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold text-slate-400">{{ tgl_indo(explode(' ', $rows->tanggal)[0]) }}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                             <a href="#" class="h-8 w-8 inline-flex items-center justify-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Donation Modal -->
    <template x-teleport="body">
        <div x-show="showAddModal" 
             class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            
            <div class="bg-white w-full max-w-2xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showAddModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-emerald-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 bg-emerald-500 text-white rounded-xl flex items-center justify-center shadow-lg transform rotate-2">
                            <i class="bi bi-gift text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest block">New Transaction</span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Tambah Sumbangan</h3>
                        </div>
                    </div>
                </div>

                <form action="{{ url('administrator/submit_post_add_sumbangan') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Kategori Penyumbang</label>
                            <select name="cmb_kategori_sumbangan" x-model="donatorType" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/5 transition-all">
                                <option value="1">Anonim</option>
                                <option value="2">Donatur Umum</option>
                                <option value="3">Investor Terdaftar</option>
                            </select>
                        </div>

                        <div x-show="donatorType == '3'" class="space-y-1.5" x-transition>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Pilih Unit Usaha</label>
                            <select name="cmb_nama_usaha" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-emerald-500/5 transition-all">
                                <option value="">- Pilih Usaha -</option>
                                @foreach($usaha as $u) <option value="{{ $u->id_usaha }}">{{ $u->nama_usaha }}</option> @endforeach
                            </select>
                        </div>

                        <div x-show="donatorType == '2'" class="grid grid-cols-1 md:grid-cols-2 gap-4" x-transition>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Donatur</label>
                                <input type="text" name="text_title_new" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alamat (Opsional)</label>
                                <input type="text" name="text_alamat_new" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nominal (Rp)</label>
                                <input type="number" name="text_minimal_pembayaran" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Metode Pembayaran</label>
                                <select name="text_namapngg_new" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">
                                    <option value="1">Manual Transfer Bank</option>
                                    <option value="2">Cash / Tunai</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Keterangan / Doa</label>
                            <textarea name="text_email_usaha_new" rows="2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold"></textarea>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Bukti Transfer (Jika ada)</label>
                            <input type="file" name="f_upload_gambar_mobile" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="showAddModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Tutup</button>
                        <button type="submit" class="px-8 py-2.5 bg-emerald-500 text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-emerald-50 hover:bg-emerald-600 transition-all">Konfirmasi Dana</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
    function export_pdf() {
        let dateawal = document.getElementById('dateawal')?.value || '';
        let dateakhir = document.getElementById('dateakhir')?.value || '';
        window.open('{{ url('administrator/export_sumbangan_pdf') }}?dateawal=' + dateawal + '&dateakhir=' + dateakhir, '_blank');
    }
</script>

@stop
