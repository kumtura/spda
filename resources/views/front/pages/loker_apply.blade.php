@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-6 pb-24 space-y-6">

    <!-- Back -->
    <a href="{{ route('public.loker.detail', $loker->id_loker) }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-[#00a6eb] text-xs font-medium transition-colors">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-slate-800 tracking-tight">Lamar Pekerjaan</h1>
        <p class="text-slate-400 text-[10px] mt-1">{{ $loker->judul }} - {{ $loker->usaha->detail->nama_usaha ?? 'Unit Usaha' }}</p>
    </div>

    <form action="{{ route('public.loker.apply', $loker->id_loker) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        @if(session('error'))
        <div class="bg-red-50 border border-red-100 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-circle text-red-600 text-lg shrink-0"></i>
                <div>
                    <p class="text-xs font-bold text-red-700 mb-1">Error</p>
                    <p class="text-[10px] text-red-600">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-100 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i class="bi bi-exclamation-circle text-red-600 text-lg shrink-0"></i>
                <div>
                    <p class="text-xs font-bold text-red-700 mb-1">Terjadi Kesalahan</p>
                    <ul class="text-[10px] text-red-600 space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <i class="bi bi-info-circle text-[#00a6eb] text-lg shrink-0"></i>
                <div>
                    <p class="text-xs font-bold text-slate-700 mb-1">Informasi Penting</p>
                    <p class="text-[10px] text-slate-600 leading-relaxed">Pastikan nomor telepon/WhatsApp yang Anda masukkan aktif dan benar. Perusahaan akan menghubungi Anda melalui kontak tersebut untuk proses interview.</p>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" name="nama" required placeholder="Nama lengkap Anda"
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all">
        </div>

        <div>
            <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Email <span class="text-rose-500">*</span></label>
            <input type="email" name="email" required placeholder="email@example.com"
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all">
        </div>

        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                <select name="jenis_kelamin" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all">
                    <option value="">Pilih</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Umur <span class="text-rose-500">*</span></label>
                <input type="number" name="umur" required placeholder="Umur" min="17" max="65"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all">
            </div>
        </div>

        <div>
            <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Alamat <span class="text-rose-500">*</span></label>
            <textarea name="alamat" rows="2" required placeholder="Alamat lengkap"
                      class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all resize-none"></textarea>
        </div>

        <div>
            <label class="block text-[10px] font-medium text-slate-500 mb-1.5">No. Telepon / WhatsApp <span class="text-rose-500">*</span></label>
            <input type="text" name="no_telp" required placeholder="08xxxxxxxxxx"
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all">
            <p class="text-[9px] text-amber-600 mt-1.5 flex items-center gap-1">
                <i class="bi bi-info-circle"></i> Nomor ini akan digunakan untuk menghubungi Anda
            </p>
        </div>

        <div>
            <label class="block text-[10px] font-medium text-slate-500 mb-1.5">Upload CV / Dokumen Pendukung</label>
            <input type="file" name="files[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" 
                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-xs text-slate-600 focus:ring-2 focus:ring-slate-500/20 focus:border-slate-500 outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-[#00a6eb] file:text-white hover:file:bg-[#0090d0]">
            <p class="text-[9px] text-slate-400 mt-1.5">Format: PDF, DOC, DOCX, JPG, PNG (Maks 5 file, 2MB per file)</p>
        </div>

        <button type="submit" class="w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white font-medium py-3.5 rounded-xl shadow-lg transition-all text-sm">
            <i class="bi bi-send-fill mr-2"></i> Kirim Lamaran
        </button>
    </form>
</div>
@endsection
