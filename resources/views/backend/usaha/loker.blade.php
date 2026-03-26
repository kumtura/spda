@extends('mobile_layout')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('administrator/') }}" class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400">
            <i class="bi bi-chevron-left text-lg"></i>
        </a>
        <h1 class="text-xl font-black tracking-tight text-slate-800">Post Lowongan Kerja</h1>
    </div>

    <!-- Info Box -->
    <div class="bg-emerald-50 border border-emerald-100 rounded-3xl p-5 mb-8">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-emerald-500 text-white rounded-lg flex items-center justify-center shrink-0">
                <i class="bi bi-person-plus text-md"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Cari Karyawan</p>
                <p class="text-[11px] text-slate-600 font-medium leading-relaxed">
                    Posting lowongan kerja Anda di sini. Lowongan akan muncul di database tenaga kerja desa dan membantu warga menemukan pekerjaan.
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ url('administrator/submit_post_add_tenagakerja') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Posisi / Nama Pekerjaan</label>
            <input type="text" name="posisi" placeholder="Contoh: Staff Gudang" required
                class="w-full bg-slate-50 border-b-2 border-slate-100 focus:border-emerald-500 transition-colors py-4 px-1 text-lg font-black text-slate-800 focus:outline-none placeholder-slate-300">
        </div>

        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Kategori Keahlian</label>
            <select name="kategori" required
                class="w-full bg-slate-50 border-b-2 border-slate-100 focus:border-emerald-500 transition-colors py-4 px-1 text-sm font-bold text-slate-800 focus:outline-none appearance-none">
                <option value="">Pilih Kategori</option>
                <option value="Administrasi">Administrasi</option>
                <option value="Produksi">Produksi</option>
                <option value="Pemasaran">Pemasaran</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Deskripsi Pekerjaan</label>
            <textarea name="deskripsi" rows="4" placeholder="Jelaskan tanggung jawab dan syarat..." required
                class="w-full bg-slate-50 border-b-2 border-slate-100 focus:border-emerald-500 transition-colors py-4 px-1 text-sm font-medium text-slate-800 focus:outline-none placeholder-slate-300 resize-none"></textarea>
        </div>

        <div>
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2 px-1">Gaji / Range (Opsional)</label>
            <input type="text" name="gaji" placeholder="Contoh: 3jt - 4jt" 
                class="w-full bg-slate-50 border-b-2 border-slate-100 focus:border-emerald-500 transition-colors py-4 px-1 text-sm font-bold text-slate-800 focus:outline-none placeholder-slate-300">
        </div>

        <div class="pt-4">
            <button type="submit" 
                class="w-full bg-emerald-500 hover:bg-emerald-600 py-4 rounded-3xl text-white font-black tracking-tight shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2">
                Posting Sekarang
                <i class="bi bi-send-fill text-sm"></i>
            </button>
        </div>
    </form>
</div>
@endsection
