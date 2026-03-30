@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white min-h-screen pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-5 pt-8 pb-6">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ url('administrator/kelian/tiket/objek') }}" class="h-8 w-8 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="bi bi-arrow-left text-white"></i>
            </a>
            <div>
                <h1 class="text-lg font-black text-white">Tambah Objek Wisata</h1>
                <p class="text-[10px] text-white/70">Buat objek wisata baru</p>
            </div>
        </div>
    </div>

    <div class="px-5 -mt-3">
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-5">
            <form action="{{ url('administrator/kelian/tiket/objek/store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Nama Objek Wisata <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_objek" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Contoh: Pantai Pererenan">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Deskripsi <span class="text-rose-500">*</span></label>
                        <textarea name="deskripsi" rows="4" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Jelaskan tentang objek wisata ini..."></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Alamat <span class="text-rose-500">*</span></label>
                        <textarea name="alamat" rows="2" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="Alamat lengkap objek wisata"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Harga Tiket (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="harga_tiket" required
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="50000">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Kapasitas Harian</label>
                        <input type="number" name="kapasitas_harian"
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]"
                            placeholder="100">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Jam Buka</label>
                            <input type="time" name="jam_buka"
                                class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-2">Jam Tutup</label>
                            <input type="time" name="jam_tutup"
                                class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-2">Foto Objek Wisata</label>
                        <input type="file" name="foto" accept="image/*"
                            class="w-full px-3 py-2.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#00a6eb]">
                        <p class="text-[10px] text-slate-500 mt-1">Max 2MB, format: JPG, PNG</p>
                    </div>

                    <button type="submit" class="w-full py-3 bg-[#00a6eb] text-white text-sm font-black rounded-xl shadow-lg hover:shadow-xl transition-all">
                        <i class="bi bi-save mr-2"></i>Simpan Objek Wisata
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
