@extends('mobile_layout_public')

@section('content')
<div class="bg-white min-h-screen pb-32" x-data="{ 
    search: '', 
    dateFilter: '',
    shouldShowItem(title, desc, date) {
        const searchMatch = !this.search || title.toLowerCase().includes(this.search.toLowerCase()) || desc.toLowerCase().includes(this.search.toLowerCase());
        const dateMatch = !this.dateFilter || date === this.dateFilter;
        return searchMatch && dateMatch;
    },
    countVisibleItems(date) {
        // Find cards with this date and check their visibility
        const cards = document.querySelectorAll(`.agenda-card[data-date='${date}']`);
        let count = 0;
        cards.forEach(card => {
            if (this.shouldShowItem(card.dataset.title, card.dataset.desc, date)) count++;
        });
        return count;
    },
    isTimelineEmpty() {
        if (!this.search && !this.dateFilter) return false;
        // Find all agenda cards and check if any are visible
        const items = document.querySelectorAll('.agenda-card');
        return Array.from(items).every(item => {
            return !this.shouldShowItem(item.dataset.title, item.dataset.desc, item.dataset.date);
        });
    }
}">
    <!-- Header (Matching Payment Page Style) -->
    <div class="bg-linear-to-br from-[#00a6eb] to-[#0090d0] px-6 pt-12 pb-16 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <h1 class="text-2xl font-bold mb-2 tracking-tight uppercase">Agenda Desa Adat</h1>
            <p class="text-white/80 text-xs font-medium">Jadwal kegiatan, upacara adat, dan event penting desa.</p>
        </div>
    </div>

    <!-- 1. Search & Date Filter Area -->
    <div class="px-5 -mt-6 relative z-30">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
            <div class="flex items-center gap-3">
                <!-- Search Bar -->
                <div class="flex-1 relative">
                    <i class="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-[10px]"></i>
                    <input type="text" x-model="search" placeholder="Cari kegiatan..." 
                           class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-9 pr-4 py-3 text-[12px] font-medium text-slate-600 focus:ring-2 focus:ring-blue-100 focus:border-[#00a6eb]/50 transition-all outline-none">
                    <button x-show="search" @click="search = ''" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                        <i class="bi bi-x-circle-fill text-slate-200 hover:text-slate-400"></i>
                    </button>
                </div>
                <!-- Date Picker -->
                <div class="relative w-32">
                    <i class="bi bi-calendar-event absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-300 text-[10px] pointer-events-none"></i>
                    <input type="date" x-model="dateFilter" 
                           class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-9 pr-3 py-3 text-[12px] font-medium text-slate-600 focus:ring-2 focus:ring-blue-100 focus:border-[#00a6eb]/50 transition-all outline-none">
                    <button x-show="dateFilter" @click="dateFilter = ''" class="absolute -right-1 -top-1 h-5 w-5 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center border border-rose-100 shadow-sm transition-all active:scale-90">
                        <i class="bi bi-x text-[12px]"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Category Filter (Moved Outside, above timeline) -->
    @if($kategori_list->count() > 0)
    <div class="px-5 mt-6 mb-2">
        <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-2 px-2 pb-1">
            <a href="{{ route('public.agenda') }}"
               class="shrink-0 px-5 py-2 rounded-full text-[11px] font-semibold border transition-all active:scale-95 {{ $selected_category == 'all' ? 'bg-[#00a6eb] text-white border-[#00a6eb] shadow-md shadow-blue-100' : 'bg-white text-slate-500 border-slate-100 hover:border-[#00a6eb]/20' }}">
                Semua
            </a>
            @foreach($kategori_list as $kat)
            <a href="{{ route('public.agenda', ['kategori' => $kat->id_kategori_agenda]) }}"
               class="shrink-0 px-5 py-2 rounded-full text-[11px] font-semibold border transition-all active:scale-95 {{ $selected_category == $kat->id_kategori_agenda ? 'bg-[#00a6eb] text-white border-[#00a6eb] shadow-md shadow-blue-100' : 'bg-white text-slate-500 border-slate-100 hover:border-[#00a6eb]/20' }}">
                {{ $kat->nama_kategori }}
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Timeline Content -->
    <div class="px-5 mt-4 relative z-20">
        <div class="relative px-1">
            <!-- Global Timeline Line -->
            <div class="absolute left-[18px] top-4 bottom-4 w-0.5 bg-slate-100"></div>

            <div class="space-y-8 min-h-[400px]">
                @php 
                    $groupedAgendas = $agendas->groupBy('tanggal_agenda'); 
                @endphp
                
                @forelse($groupedAgendas as $date => $dayItems)
                @php 
                    $isToday = \Carbon\Carbon::parse($date)->isToday();
                @endphp
                <div class="relative pl-11 flex flex-col gap-4 group-container" 
                     data-group-date="{{ $date }}"
                     x-show="countVisibleItems('{{ $date }}') > 0"
                     x-data="{ expanded: true }"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <!-- Date Indicator (Interactive Marker) -->
                    <div class="absolute left-0 top-1.5 flex flex-col items-center">
                        <button @click="expanded = !expanded" 
                                class="h-9 w-9 bg-white rounded-lg border border-slate-200 flex flex-col items-center justify-center relative z-10 shadow-sm transition-all transform active:scale-95 cursor-pointer hover:bg-slate-50 overflow-hidden"
                                :class="expanded ? 'border-[#00a6eb] ring-2 ring-blue-50' : 'opacity-60 border-slate-100 shadow-none'">
                            <span class="text-[6px] font-bold uppercase leading-none mb-0.5" :class="expanded ? 'text-[#00a6eb]' : 'text-slate-400'">{{ \Carbon\Carbon::parse($date)->translatedFormat('M') }}</span>
                            <span class="text-xs font-bold leading-none" :class="expanded ? 'text-slate-700' : 'text-slate-500'">{{ \Carbon\Carbon::parse($date)->translatedFormat('d') }}</span>
                            
                            <!-- Small Interactive indicator -->
                            <div class="absolute -right-0 -bottom-0 h-2.5 w-2.5 bg-white border border-slate-100 rounded-tl-md flex items-center justify-center">
                                <i class="bi text-[6px]" :class="expanded ? 'bi-dash text-[#00a6eb]' : 'bi-plus text-slate-400'"></i>
                            </div>
                        </button>
                    </div>

                    <!-- Collapsed Summary -->
                    <div x-show="!expanded" @click="expanded = true"
                         class="cursor-pointer bg-slate-50/70 border border-slate-100/50 rounded-xl py-2.5 px-4 flex items-center justify-between transition-colors hover:bg-slate-100/50">
                        <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest" x-text="`${countVisibleItems('{{ $date }}')} Kegiatan`"></span>
                        <div class="flex items-center gap-2">
                             @if($isToday)
                             <span class="text-[8px] font-bold text-[#00a6eb] uppercase tracking-wider">Hari Ini</span>
                             @endif
                             <i class="bi bi-chevron-down text-slate-300 text-xs"></i>
                        </div>
                    </div>

                    <!-- Activities List -->
                    <div class="space-y-4" x-show="expanded" x-collapse>
                        @foreach($dayItems as $index => $agenda)
                        <div data-date="{{ $date }}" 
                             data-title="{{ addslashes($agenda->judul_agenda) }}" 
                             data-desc="{{ addslashes(strip_tags($agenda->deskripsi_agenda)) }}"
                             x-show="shouldShowItem($el.dataset.title, $el.dataset.desc, '{{ $date }}')"
                             class="flex items-stretch bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden group hover:border-[#00a6eb]/30 transition-all duration-300 agenda-card">
                            
                            <!-- Left: Thumbnail (Full Height) -->
                            <div class="w-24 min-h-[110px] bg-slate-50 overflow-hidden relative group-hover:opacity-95">
                                @if($agenda->foto_agenda)
                                <img src="{{ asset('storage/agenda/'.$agenda->foto_agenda) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $agenda->judul_agenda }}">
                                @else
                                <div class="h-full w-full flex items-center justify-center text-slate-100">
                                    <i class="bi bi-calendar-event text-xl"></i>
                                </div>
                                @endif
                                
                                @if($isToday)
                                <div class="absolute top-0 left-0 bg-[#00a6eb] text-white text-[6px] font-bold px-2 py-0.5 rounded-br-lg uppercase tracking-widest shadow-sm">TODAY</div>
                                @endif
                            </div>

                            <!-- Right: Content -->
                            <div class="flex-1 p-4 flex flex-col justify-between min-w-0">
                                <div class="space-y-1.5">
                                    <span class="text-[9px] font-semibold text-[#00a6eb] uppercase tracking-widest truncate">
                                        {{ $agenda->kategori->nama_kategori ?? 'Umum' }}
                                    </span>
                                    <h3 class="text-[13px] font-bold text-slate-800 leading-tight line-clamp-1 group-hover:text-[#00a6eb] transition-colors">{{ $agenda->judul_agenda }}</h3>
                                    <p class="text-[11px] text-slate-500 leading-relaxed line-clamp-2 font-medium">{{ strip_tags($agenda->deskripsi_agenda) }}</p>
                                </div>

                                <div class="pt-3 mt-1 border-t border-slate-50 flex items-center gap-4">
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <i class="bi bi-clock text-slate-300 text-[10px]"></i>
                                        <span class="text-[10px] font-medium text-slate-500 truncate uppercase">
                                            {{ date('H:i', strtotime($agenda->waktu_agenda)) }} 
                                            @if($agenda->status_selesai == 'selesai')
                                                - Selesai
                                            @elseif($agenda->waktu_selesai_data)
                                                - {{ date('H:i', strtotime($agenda->waktu_selesai_data)) }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 min-w-0">
                                        <i class="bi bi-geo-alt text-slate-300 text-[10px]"></i>
                                        <span class="text-[10px] font-medium text-slate-500 truncate">{{ $agenda->lokasi_agenda ?: 'TBA' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="py-20 text-center">
                    <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-200 border border-slate-100">
                        <i class="bi bi-calendar-x text-3xl"></i>
                    </div>
                    <p class="text-[11px] text-slate-400 font-medium uppercase tracking-widest">Belum ada agenda desa</p>
                </div>
                @endforelse

                <!-- 3. Simple & Professional Empty State -->
                <div x-show="isTimelineEmpty()" x-cloak class="py-24 text-center">
                    <div class="mb-4">
                        <i class="bi bi-search text-slate-200 text-4xl"></i>
                    </div>
                    <p class="text-slate-400 text-sm font-medium mb-8">Tidak ada agenda yang cocok</p>
                    <button @click="search = ''; dateFilter = ''" 
                            class="inline-flex items-center gap-2 px-8 py-3 bg-[#00a6eb] text-white rounded-full text-[10px] font-bold uppercase tracking-widest hover:brightness-110 transition-all active:scale-95 shadow-lg shadow-blue-100">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
[x-cloak] { display: none !important; }

/* Date Picker Reset */
input[type="date"]::-webkit-calendar-picker-indicator {
    background: transparent;
    bottom: 0;
    color: transparent;
    cursor: pointer;
    height: auto;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    width: auto;
}
</style>
@endsection
