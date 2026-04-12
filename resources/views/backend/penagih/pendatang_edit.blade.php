@extends('mobile_layout')

@section('isi_menu')
<div class="bg-white pb-24">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#00a6eb] to-[#0090d0] px-4 pt-8 pb-12 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <a href="{{ url('administrator/penagih/pendatang/detail/'.$pendatang->id_pendatang) }}" class="inline-flex items-center gap-1 text-white/80 hover:text-white text-xs font-bold transition-colors mb-6 relative z-10">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            
            <h1 class="text-2xl font-black mb-1">Edit Pendatang</h1>
            <p class="text-white/80 text-xs font-medium uppercase tracking-widest">Update informasi kependudukan</p>
        </div>
    </div>

    <!-- Form Container -->
    <div class="px-4 -mt-6 relative z-10">
        <form method="POST" action="{{ url('administrator/penagih/pendatang/update/'.$pendatang->id_pendatang) }}" 
              class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 space-y-8">
            @csrf
            @method('PUT')
            
            @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 rounded-xl p-3">
                <ul class="text-xs text-rose-600 space-y-1">
                    @foreach($errors->all() as $error)
                    <li><i class="bi bi-exclamation-circle mr-1"></i>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Identitas Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 px-1">
                    <div class="w-1 h-4 bg-[#00a6eb] rounded-full"></div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Identitas</h3>
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $pendatang->nama) }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">NIK <span class="text-rose-500">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik', $pendatang->nik) }}" required maxlength="20"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                </div>
            </div>

            <!-- Kontak & Domisili Section -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 px-1">
                    <div class="w-1 h-4 bg-[#00a6eb] rounded-full"></div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kontak & Domisili</h3>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Asal <span class="text-rose-500">*</span></label>
                    <input type="text" name="asal" value="{{ old('asal', $pendatang->asal) }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">No. HP / WhatsApp <span class="text-rose-500">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $pendatang->no_hp) }}" required
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                </div>
                
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Alamat Lengkap</label>
                    <textarea name="alamat_tinggal" rows="3"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all resize-none">{{ old('alamat_tinggal', $pendatang->alamat_tinggal) }}</textarea>
                </div>
            </div>

            <!-- Lama Tinggal Section -->
            <div class="space-y-4" x-data="{ belumYakin: {{ $pendatang->tinggal_belum_yakin ? 'true' : 'false' }} }">
                <div class="flex items-center gap-2 px-1">
                    <div class="w-1 h-4 bg-emerald-500 rounded-full"></div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Lama Tinggal</h3>
                </div>

                <div class="flex items-center justify-between bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                    <div>
                        <p class="text-xs font-bold text-slate-700">Belum yakin / Not sure yet</p>
                        <p class="text-[9px] text-slate-400">Centang jika belum menentukan durasi tinggal</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="tinggal_belum_yakin" value="1" x-model="belumYakin" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00a6eb]"></div>
                    </label>
                </div>

                <div x-show="!belumYakin" x-transition class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Dari Tanggal</label>
                        <input type="date" name="tinggal_dari" value="{{ $pendatang->tinggal_dari ? $pendatang->tinggal_dari->format('Y-m-d') : '' }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Sampai Tanggal</label>
                        <input type="date" name="tinggal_sampai" value="{{ $pendatang->tinggal_sampai ? $pendatang->tinggal_sampai->format('Y-m-d') : '' }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all">
                    </div>
                </div>
            </div>

            <!-- Pengaturan Punia Section -->
            @php
                $settings = json_decode(file_get_contents(storage_path('app/settings.json')), true);
                $puniaGlobal = $settings['punia_pendatang_global'] ?? 0;
            @endphp
            <div class="space-y-4">
                <div class="flex items-center gap-2 px-1">
                    <div class="w-1 h-4 bg-amber-500 rounded-full"></div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pengaturan Iuran (Punia)</h3>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 space-y-5">
                    <!-- Global Toggle -->
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold text-slate-700">Gunakan tarif global</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Tarif saat ini: Rp {{ number_format($puniaGlobal, 0, ',', '.') }} / bulan</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="use_global_punia" value="1" {{ $pendatang->use_global_punia ? 'checked' : '' }} class="sr-only peer" onchange="toggleGlobalPunia(this)">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00a6eb]"></div>
                        </label>
                    </div>

                    <!-- Manual Amount Input -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal Punia Bulanan <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                            <input type="number" name="punia_rutin_bulanan" required min="0" step="1000" value="{{ old('punia_rutin_bulanan', $pendatang->punia_rutin_bulanan) }}"
                                   id="punia-amount-input"
                                   class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-[#00a6eb]/10 transition-all {{ $pendatang->use_global_punia ? 'opacity-50 cursor-not-allowed' : '' }}"
                                   placeholder="0"
                                   {{ $pendatang->use_global_punia ? 'disabled' : '' }}>
                        </div>
                        <p class="text-[9px] text-slate-400 leading-relaxed px-1 mt-1 italic">Aktifkan nominal khusus jika pendatang ini memiliki keringanan atau kesepakatan berbeda.</p>
                    </div>
                </div>
            </div>
            
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-[#00a6eb] to-[#0090d0] text-white py-4 rounded-2xl font-black text-sm shadow-xl shadow-blue-200 hover:shadow-2xl hover:shadow-blue-300 transition-all active:scale-[0.98]">
                <i class="bi bi-check-circle-fill mr-2"></i> Simpan Perubahan Data
            </button>
        </form>
    </div>
</div>

<script>
function toggleGlobalPunia(checkbox) {
    const input = document.getElementById('punia-amount-input');
    if (checkbox.checked) {
        input.disabled = true;
        input.classList.add('opacity-50');
        input.classList.add('cursor-not-allowed');
    } else {
        input.disabled = false;
        input.classList.remove('opacity-50');
        input.classList.remove('cursor-not-allowed');
    }
}
</script>
@endsection
