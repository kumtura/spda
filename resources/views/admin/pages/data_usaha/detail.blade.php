@extends($base_layout ?? 'index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    activeTab: 'detail',
    showEditModal: false,
    showPayModal: false,
    showDetailPayModal: false,
    
    // Modal Data
    selectedMonth: '',
    selectedMonthName: '',
    selectedYear: '{{ date('Y') }}',
    
    // Pay Detail Data
    payDetail: {
        bulan: '',
        tahun: '',
        tanggal: '',
        jumlah: '',
        extra: '',
        metode: '',
        bukti: ''
    },

    openPay(index, name) {
        this.selectedMonth = index;
        this.selectedMonthName = name;
        this.showPayModal = true;
    },

    openDetailPay(id) {
        $.ajax({
            type: 'GET',
            url: '{{ url('api/get_pembayaran_detail') }}/' + id,
            dataType: 'json',
            success: (data) => {
                const months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                this.payDetail = {
                    bulan: months[data[0].bulan],
                    tahun: data[0].tahun,
                    tanggal: data[0].tanggal_pembayaran,
                    jumlah: 'Rp. ' + data[0].jumlah_dana,
                    extra: 'Rp. ' + data[0].charge,
                    metode: data[0].metode,
                    bukti: '{{ url('storage/bukti_pembayaran') }}/' + data[0].bukti_pembayaran
                };
                this.showDetailPayModal = true;
            }
        });
    }
}">

    <!-- Top Action Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ url('administrator/data_usaha') }}" class="h-10 w-10 flex items-center justify-center bg-white rounded-xl shadow-sm border border-slate-200 text-slate-400 hover:text-primary-light transition-colors">
                <i class="bi bi-arrow-left text-lg"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Profil Management</h1>
                <p class="text-slate-500 font-medium text-sm">Dashboard kendali unit usaha dan administrasi.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showEditModal = true" class="bg-white border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl font-bold text-xs shadow-sm hover:shadow-md hover:border-primary-light hover:text-primary-light transition-all">
                <i class="bi bi-pencil-square mr-1.5"></i> Edit Data Usaha
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left Sidebar: Unit Profile Card -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm relative">
                <!-- Decorative background -->
                <div class="absolute top-0 left-0 w-full h-24 bg-primary-light opacity-5"></div>
                
                <div class="p-6 pt-10 text-center relative">
                    <!-- Logo Upload -->
                    <div class="relative inline-block group mb-6">
                        <div class="h-32 w-32 rounded-2xl bg-white p-2 shadow-lg border border-slate-100 overflow-hidden relative">
                            @php
                                $logoPath = file_exists(public_path('usaha/icon/'.$rows->logo)) 
                                    ? 'usaha/icon/'.$rows->logo 
                                    : 'storage/usaha/icon/'.$rows->logo;
                            @endphp
                            <img src="{{ asset($logoPath) }}" id="logo_img_icon" class="w-full h-full object-contain rounded-xl">
                            <div id="logo_usaha_loader" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center" style="display: none;">
                                <div class="animate-spin rounded-full h-6 w-6 border-2 border-white border-t-transparent"></div>
                            </div>
                        </div>
                        <label for="f_upload" class="absolute -bottom-2 -right-2 h-9 w-9 bg-primary-light text-white rounded-lg flex items-center justify-center shadow-lg cursor-pointer hover:bg-primary-dark transition-all transform hover:scale-105 active:scale-95 border-2 border-white">
                            <i class="bi bi-camera-fill text-sm"></i>
                        </label>
                        <input type="file" id="f_upload" class="hidden" onchange="upload_gambar('{{ $rows->id_detail_usaha }}')">
                    </div>

                    <div class="space-y-1.5">
                        <span class="inline-flex items-center px-3 py-1 rounded-md bg-blue-50 text-primary-light text-[9px] font-black uppercase tracking-widest border border-blue-100">
                            {{ $rows->nama_kategori_usaha ?? 'Unit Usaha' }}
                        </span>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight leading-tight">{{ $rows->nama_usaha }}</h2>
                        <div class="flex items-center justify-center gap-1.5 text-emerald-600 font-bold text-xs">
                            <i class="bi bi-whatsapp"></i>
                            <span>{{ $rows->no_wa }}</span>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-2 gap-3">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Daftar</p>
                            <p class="text-[11px] font-black text-slate-700">{{ tgl_indo($rows->tanggal_daftar) }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">ID</p>
                            <p class="text-[11px] font-black text-slate-700">#{{ str_pad($rows->id_usaha, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contact List -->
                <div class="p-6 bg-slate-50/50 border-t border-slate-100 space-y-4">
                    <div class="flex items-center gap-4 group">
                        <div class="h-9 w-9 shrink-0 bg-white rounded-lg flex items-center justify-center text-slate-400 shadow-sm border border-slate-200 group-hover:text-primary-light group-hover:border-blue-100 transition-all">
                            <i class="bi bi-envelope text-lg"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Email</p>
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $rows->email_usaha }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 group">
                        <div class="h-9 w-9 shrink-0 bg-white rounded-lg flex items-center justify-center text-slate-400 shadow-sm border border-slate-200 group-hover:text-sky-600 group-hover:border-sky-100 transition-all">
                            <i class="bi bi-globe2 text-lg"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Website</p>
                            <p class="text-xs font-bold text-slate-700 truncate">{{ $rows->website_url ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Links -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-around">
                <a href="{{ $rows->facebook_url }}" target="_blank" class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-primary-light hover:text-white transition-all shadow-sm">
                    <i class="bi bi-facebook text-lg"></i>
                </a>
                <a href="{{ $rows->twitter_url }}" target="_blank" class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-pink-500 hover:text-white transition-all shadow-sm">
                    <i class="bi bi-instagram text-lg"></i>
                </a>
                <a href="#" class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-emerald-500 hover:text-white transition-all shadow-sm">
                    <i class="bi bi-geo-alt-fill text-lg"></i>
                </a>
            </div>
        </div>

        <!-- Right Side: Content Tabs -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm flex flex-col h-full">
                <!-- Tabs Nav -->
                <div class="flex overflow-x-auto bg-slate-50/50 border-b border-slate-100 no-scrollbar">
                    <button @click="activeTab = 'detail'" :class="activeTab === 'detail' ? 'bg-white text-primary-light border-b-2 border-primary-light' : 'text-slate-400'"
                            class="px-6 py-4 font-black text-[10px] uppercase tracking-widest transition-all">Status</button>
                    <button @click="activeTab = 'pj'" :class="activeTab === 'pj' ? 'bg-white text-primary-light border-b-2 border-primary-light' : 'text-slate-400'"
                            class="px-6 py-4 font-black text-[10px] uppercase tracking-widest transition-all">PJ Usaha</button>
                    <button @click="activeTab = 'punia'" :class="activeTab === 'punia' ? 'bg-white text-primary-light border-b-2 border-primary-light' : 'text-slate-400'"
                            class="px-6 py-4 font-black text-[10px] uppercase tracking-widest transition-all">Punia Wajib</button>
                    <button @click="activeTab = 'sumbangan'" :class="activeTab === 'sumbangan' ? 'bg-white text-primary-light border-b-2 border-primary-light' : 'text-slate-400'"
                            class="px-6 py-4 font-black text-[10px] uppercase tracking-widest transition-all">Sumbangan</button>
                    <button @click="activeTab = 'tenaga'" :class="activeTab === 'tenaga' ? 'bg-white text-primary-light border-b-2 border-primary-light' : 'text-slate-400'"
                            class="px-6 py-4 font-black text-[10px] uppercase tracking-widest transition-all">Tenaga Kerja</button>
                </div>

                <div class="p-6 flex-1 min-h-[500px]">
                    <!-- Tab: Detail -->
                    <div x-show="activeTab === 'detail'" class="space-y-6" x-transition>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="p-5 bg-slate-50 border border-slate-100 rounded-xl">
                                    <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Geolokasi</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-start gap-2.5">
                                            <i class="bi bi-geo-alt text-primary-light"></i>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-400 uppercase">Banjar</p>
                                                <p class="text-xs font-bold text-slate-700">{{ $rows->nama_banjar }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-2.5">
                                            <i class="bi bi-map text-primary-light"></i>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-400 uppercase">Alamat</p>
                                                <p class="text-xs font-bold text-slate-700 leading-relaxed">{{ $rows->alamat_banjar }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 bg-slate-50 border border-slate-100 rounded-xl">
                                    <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-3">Keuangan</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-start gap-2.5">
                                            <i class="bi bi-cash-stack text-emerald-600"></i>
                                            <div>
                                                <p class="text-[9px] font-black text-slate-400 uppercase">Minimal Punia</p>
                                                <p class="text-xs font-black text-slate-800">Rp. {{ number_format($rows->minimal_bayar, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-100 min-h-[250px] shadow-inner">
                                {!! $rows->google_maps !!}
                            </div>
                        </div>
                    </div>

                    <!-- Tab: PJ -->
                    <div x-show="activeTab === 'pj'" class="max-w-xl mx-auto space-y-6" x-transition>
                        <div class="p-6 bg-slate-50 border border-slate-100 rounded-2xl">
                            <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-200">
                                <div class="h-14 w-14 bg-primary-light text-white rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="bi bi-person-badge text-3xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-slate-800 tracking-tight">Penanggung Jawab</h3>
                                    <p class="text-xs font-medium text-slate-400">Identitas resmi pengelola unit.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 text-xs">
                                <div class="flex justify-between border-b border-white pb-2">
                                    <span class="text-slate-400 font-black uppercase tracking-widest text-[9px]">Nama</span>
                                    <span class="font-bold text-slate-700">{{ $rows->nama }}</span>
                                </div>
                                <div class="flex justify-between border-b border-white pb-2">
                                    <span class="text-slate-400 font-black uppercase tracking-widest text-[9px]">Jabatan</span>
                                    <span class="font-bold text-slate-700">{{ $rows->status_penanggung_jawab }}</span>
                                </div>
                                <div class="flex justify-between border-b border-white pb-2">
                                    <span class="text-slate-400 font-black uppercase tracking-widest text-[9px]">Email</span>
                                    <span class="font-bold text-slate-700">{{ $rows->email }}</span>
                                </div>
                                <div class="flex justify-between border-b border-white pb-2">
                                    <span class="text-slate-400 font-black uppercase tracking-widest text-[9px]">WhatsApp</span>
                                    <span class="font-bold text-emerald-600">{{ $rows->no_wa_pngg }}</span>
                                </div>
                                <div class="flex flex-col gap-1.5">
                                    <span class="text-slate-400 font-black uppercase tracking-widest text-[9px]">Alamat Domisili</span>
                                    <p class="font-bold text-slate-700 leading-relaxed">{{ $rows->alamat }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Punia -->
                    <div x-show="activeTab === 'punia'" class="space-y-6" x-transition>
                        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                            <h3 class="font-black text-slate-800 tracking-tight">Punia Wajib Bulanan</h3>
                            <select x-model="selectedYear" class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700 outline-none">
                                <option>{{ date('Y') }}</option>
                                <option>{{ date('Y') - 1 }}</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            @php
                                $bln = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
                                $index_dana = 0;
                            @endphp
                            @foreach($bln as $idx => $name)
                                @php
                                    $sts = $arr_sts_punia[$idx];
                                    $is_locked = (date("Y")."-".str_pad(($idx+1),2,'0',STR_PAD_LEFT)."-31") < $rows->tanggal_daftar;
                                @endphp
                                <div class="p-4 rounded-xl border flex items-center justify-between transition-all group {{ $sts == 'y' ? 'bg-emerald-50 border-emerald-100' : ($is_locked ? 'opacity-40 grayscale bg-slate-50 border-slate-100' : 'bg-white border-slate-100 hover:border-blue-100 shadow-sm') }}">
                                    <div class="flex items-center gap-3">
                                        <div class="h-9 w-9 rounded-lg flex items-center justify-center font-black text-xs {{ $sts == 'y' ? 'bg-emerald-500 text-white' : 'bg-slate-100 text-slate-400' }}">
                                            @if($sts == 'y') <i class="bi bi-check-lg"></i> @else {{ ($idx+1) }} @endif
                                        </div>
                                        <div>
                                            <p class="text-xs font-black text-slate-800">{{ $name }}</p>
                                            <p class="text-[9px] font-bold text-slate-400 uppercase">{{ $sts == 'y' ? 'Lunas' : ($is_locked ? 'N/A' : 'Tertunda') }}</p>
                                        </div>
                                    </div>
                                    @if($sts == 'y')
                                        <button @click="openDetailPay('{{ $arr_danas[$index_dana]->id_dana_punia }}')" class="h-8 w-8 flex items-center justify-center bg-white border border-emerald-200 text-emerald-600 rounded-lg hover:bg-emerald-500 hover:text-white transition-all">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        @php $index_dana++; @endphp
                                    @elseif(!$is_locked)
                                        <button @click="openPay('{{ $idx+1 }}', '{{ $name }}')" class="px-4 py-1.5 bg-primary-light text-white text-[9px] font-black uppercase rounded-lg hover:bg-primary-dark transition-all">Bayar</button>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tab: Sumbangan -->
                    <div x-show="activeTab === 'sumbangan'" class="space-y-6" x-transition>
                        <div class="space-y-3 max-h-[350px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($sumbangan as $s)
                            <div class="p-5 bg-white border border-slate-100 rounded-2xl flex items-center justify-between hover:border-blue-100 transition-all shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="h-11 w-11 bg-blue-50 text-primary-light rounded-xl flex items-center justify-center text-xl">
                                        <i class="bi bi-heart-pulse"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-slate-400 uppercase mb-0.5">{{ tgl_indo($s->tanggal) }}</p>
                                        <p class="text-xs font-black text-slate-800">Rp. {{ number_format($s->nominal, 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-slate-500 italic">"{{ $s->deskripsi }}"</p>
                                    </div>
                                </div>
                                <span class="text-[9px] font-black text-slate-300 uppercase">#SUMB-{{ $s->id_sumbangan }}</span>
                            </div>
                            @endforeach
                        </div>
                        <div class="p-6 bg-slate-900 rounded-2xl shadow-xl flex items-center justify-between text-white relative overflow-hidden">
                            <div class="absolute right-0 bottom-0 opacity-10"><i class="bi bi-piggy-bank text-9xl"></i></div>
                            <div class="relative">
                                <p class="text-[9px] font-black text-blue-300 uppercase tracking-widest mb-1">Total Akumulasi</p>
                                <h3 class="text-2xl font-black">Rp. {{ number_format($total_usaha, 0, ',', '.') }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Tab: Tenaga Kerja -->
                    <div x-show="activeTab === 'tenaga'" class="space-y-6" x-transition>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($usaha_karyawan as $k)
                            <div class="p-4 bg-white border border-slate-200 rounded-xl hover:shadow-md transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="h-12 w-12 rounded-lg bg-slate-50 border border-slate-100 overflow-hidden shrink-0">
                                        @if($k->foto_profile)
                                            <img src="{{ asset('storage/karyawan/'.$k->foto_profile) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold uppercase">{{ substr($k->nama, 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <h4 class="text-xs font-black text-slate-800 truncate">{{ $k->nama }}</h4>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">NIK. {{ str_pad($k->id_tenaga_kerja, 6, '0', STR_PAD_LEFT) }}</p>
                                    </div>
                                    <a href="{{ url('administrator/detail_tenaga_kerja/'.$k->id_tenaga_kerja) }}" class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:text-primary-light transition-all shadow-sm">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals (Edit, Pay, DetailPay) - standardized with reduced rounded corners -->
    <template x-teleport="body">
        <div x-show="showEditModal" class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8" x-cloak>
            <div class="bg-white w-full max-w-2xl rounded-2xl overflow-hidden shadow-2xl border border-slate-200" @click.away="showEditModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Update Data Usaha</h3>
                    <button @click="showEditModal = false" class="text-slate-400 hover:text-rose-500"><i class="bi bi-x-lg"></i></button>
                </div>
                <form action="{{ url('administrator/update_post_add_usaha') }}" method="POST" class="p-6 space-y-5">
                    @csrf @method('PUT')
                    <input type="hidden" name="tb_hidden_usaha" value="{{ $rows->id_usaha }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Nama Usaha</label>
                            <input type="text" name="text_title_new" value="{{ $rows->nama_usaha }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Min Setoran</label>
                            <input type="number" name="text_minimal_pembayaran" value="{{ $rows->minimal_bayar }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" @click="showEditModal = false" class="px-6 py-2.5 text-[10px] font-black uppercase text-slate-400">Tutup</button>
                        <button type="submit" class="px-8 py-2.5 bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </template>

</div>

<script>
function upload_gambar(index) {
    var data = new FormData();
    data.append('file', $('#f_upload')[0].files[0]);
    data.append('_token', '{{ csrf_token() }}');
    $('#logo_usaha_loader').fadeIn('fast');
    $.ajax({
        url: '{{ url('api/upload_gambar_usaha') }}/' + index,
        type: 'POST', data: data, processData: false, contentType: false,
        success: function(r) { $('#logo_img_icon').attr('src', r); $('#logo_usaha_loader').fadeOut('slow'); }
    });
}
</script>

@stop
