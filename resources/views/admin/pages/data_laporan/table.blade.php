@extends('index')

@section('isi_menu')

<div class="space-y-6" x-data="{ 
    showAddModal: false,
    editMode: false,
    
    // Form Data
    laporanId: '',
    laporanTitle: '',
    laporanYear: '{{ date('Y') }}',

    openAdd() {
        this.editMode = false;
        this.laporanId = '';
        this.laporanTitle = '';
        this.laporanYear = '{{ date('Y') }}';
        this.showAddModal = true;
    },

    openEdit(id, title, year) {
        this.editMode = true;
        this.laporanId = id;
        this.laporanTitle = title;
        this.laporanYear = year;
        this.showAddModal = true;
    }
}">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Arsip Laporan Keuangan</h1>
            <p class="text-slate-500 font-medium text-sm">Akses dan kelola dokumen laporan keuangan tahunan dalam format digital.</p>
        </div>
        <button @click="openAdd()" 
                class="flex items-center justify-center gap-2 bg-primary-light hover:bg-primary-dark text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5">
            <i class="bi bi-file-earmark-pdf text-lg"></i>
            Tambah Laporan
        </button>
    </div>

    <!-- Reports Grid -->
    <div id="div_container_isi" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div class="col-span-full py-12 text-center">
            <div class="animate-spin h-6 w-6 border-2 border-primary-light border-t-transparent rounded-full mx-auto mb-2"></div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Memuat Arsip...</p>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <template x-teleport="body">
        <div x-show="showAddModal" 
             class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-4 py-8"
             x-cloak>
            
            <div class="bg-white w-full max-w-lg rounded-2xl overflow-hidden shadow-2xl relative border border-slate-200" @click.away="showAddModal = false">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-4">
                        <div class="h-12 w-12 rounded-xl bg-primary-light text-white flex items-center justify-center shadow-lg transform rotate-2">
                            <i class="bi bi-cloud-upload text-2xl"></i>
                        </div>
                        <div>
                            <span class="text-[9px] font-black text-primary-light uppercase tracking-widest block" x-text="editMode ? 'Modifikasi Arsip' : 'Upload Baru'"></span>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight" x-text="editMode ? 'Edit Laporan' : 'Upload Laporan'"></h3>
                        </div>
                    </div>
                </div>

                <form id="frm_warta" class="p-6 space-y-6">
                    @csrf
                    <input type="hidden" name="t_idberita" x-model="laporanId">
                    <input type="hidden" id="t_aksi_pencarian" :value="editMode ? 'edit' : ''">

                    <div class="space-y-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Judul Laporan</label>
                            <input type="text" name="textinputan" id="textinputan" required x-model="laporanTitle"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Tahun Anggaran</label>
                            <input type="number" name="tanggalinput" id="tanggalinput" required x-model="laporanYear"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:ring-4 focus:ring-primary-light/5 transition-all">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">File (PDF)</label>
                            <input type="file" name="uploadinput" id="uploadinput" 
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold outline-none">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                        <button type="button" @click="showAddModal = false" class="px-6 py-2.5 font-black text-[10px] uppercase text-slate-400">Batal</button>
                        <button type="button" onclick="tambahdata()" 
                                class="px-8 py-2.5 bg-slate-900 hover:bg-primary-light text-white rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg transition-all transform hover:-translate-y-0.5">
                            Simpan Laporan <i class="bi bi-check-lg ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        ambil_warta();
    });

    function ambil_warta() {
        const container = document.getElementById('div_container_isi');
        
        $.ajax({
            type: "get",
            url: "{{ url('administrator/ambil_listlaporan') }}",
            dataType: "json",
            success: function(data) {
                container.innerHTML = '';
                if(data.length === 0) {
                    container.innerHTML = '<div class="col-span-full py-12 text-center"><i class="bi bi-inbox text-3xl text-slate-200 block mb-2"></i><p class="text-xs font-black text-slate-400 uppercase">Belum ada laporan</p></div>';
                    return;
                }

                data.forEach(item => {
                    const card = `
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-start justify-between mb-4">
                                <div class="h-12 w-12 bg-rose-50 rounded-xl flex items-center justify-center text-rose-500 border border-rose-100 group-hover:scale-110 transition-transform">
                                    <i class="bi bi-file-earmark-pdf-fill text-2xl"></i>
                                </div>
                                <span class="px-2 py-0.5 bg-blue-50 text-primary-light rounded text-[9px] font-black uppercase tracking-widest border border-blue-100">${item.tahun}</span>
                            </div>
                            <h3 class="text-sm font-black text-slate-800 tracking-tight leading-snug mb-1 line-clamp-2">${item.title}</h3>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-6">Financial Archive • PDF</p>
                            
                            <div class="flex gap-2">
                                <a href="{{ url('storage/laporan') }}/${item.file}" target="_blank" 
                                   class="flex-1 h-9 flex items-center justify-center gap-1.5 bg-slate-900 hover:bg-primary-light text-white rounded-lg font-black text-[9px] uppercase tracking-widest transition-all">
                                    <i class="bi bi-download"></i> Unduh
                                </a>
                                <div class="flex gap-1.5">
                                    <button onclick="window.Alpine.find(document.querySelector('[x-data]')).openEdit('${item.id_laporan}', '${item.title}', '${item.tahun}')"
                                            class="h-9 w-9 flex items-center justify-center bg-white border border-slate-200 text-slate-400 rounded-lg hover:text-primary-light hover:border-primary-light transition-all shadow-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button onclick="deletedata('${item.id_laporan}')"
                                            class="h-9 w-9 flex items-center justify-center bg-white border border-slate-200 text-slate-400 rounded-lg hover:text-rose-500 hover:border-rose-100 transition-all shadow-sm">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', card);
                });
            }
        });
    }

    function tambahdata() {
        let formData = new FormData(document.getElementById('frm_warta'));
        let isEdit = document.getElementById('t_aksi_pencarian').value === 'edit';
        let targetUrl = isEdit ? "{{ url('administrator/updatelaporan') }}" : "{{ url('administrator/tambahlaporan') }}";

        $.ajax({
            url: targetUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                window.Alpine.find(document.querySelector('[x-data]')).showAddModal = false;
                ambil_warta();
            }
        });
    }

    function deletedata(id) {
        if(confirm('Hapus laporan ini?')) {
            $.ajax({
                type: "GET",
                url: "{{ url('administrator/hapuswarta') }}",
                data: { id: id },
                success: function() {
                    ambil_warta();
                }
            });
        }
    }
</script>

@stop
