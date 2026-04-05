<aside id="sidebar" 
    x-data="{ 
        openSettings: false, 
        openPunia: {{ Request::is('administrator/datapunia_wajib*') || Request::is('administrator/kategori_punia*') || Request::is('administrator/alokasi_punia*') ? 'true' : 'false' }},
        openDonasi: {{ Request::is('administrator/datasumbangan*') || Request::is('administrator/kategori_donasi*') || Request::is('administrator/program_donasi*') ? 'true' : 'false' }},
        openBlog: false,
        openTenaga: false,
        openAgenda: {{ Request::is('administrator/agenda*') || Request::is('administrator/kategori_agenda*') ? 'true' : 'false' }},
        openTicketCounter: {{ Request::is('administrator/staff_counter*') || Request::is('administrator/ticket_counter_data*') ? 'true' : 'false' }}
    }"
    :class="mobileSidebarOpen ? 'translate-x-0' : (sidebarOpen ? 'max-lg:-translate-x-full translate-x-0' : '-translate-x-full lg:translate-x-0 lg:w-20')"
    class="fixed top-0 left-0 z-50 w-64 h-screen transition-all duration-300 sidebar-gradient border-r border-white/10 overflow-y-auto no-scrollbar shadow-2xl" 
    aria-label="Sidebar">
   
    <!-- Sidebar Header / Branding -->
    <div class="h-24 flex items-center justify-between px-6 mb-2 border-b border-white/10 shrink-0">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-lg p-2">
                @if(file_exists(public_path('storage/logos/logo.png')))
                    <img src="{{ asset('storage/logos/logo.png') }}" class="h-full w-full object-contain" alt="Logo">
                @else
                    <i class="bi bi-grid-1x2-fill text-yellow-400 text-xl"></i>
                @endif
            </div>
            <div class="transition-all duration-300" x-show="sidebarOpen" x-transition:enter="delay-150 duration-300" x-transition:enter-start="opacity-0 -translate-x-4">
                <h1 class="text-white font-black text-sm tracking-tighter leading-none mb-1 uppercase">{{ $village['name'] ?? 'SPDA' }}</h1>
                <p class="text-white/40 font-bold text-[9px] uppercase tracking-widest leading-none">Desa Adat Terpadu</p>
            </div>
        </div>
       
       <!-- Sidebar Toggle (Inside Sidebar - Desktop) -->
       <button @click="sidebarOpen = !sidebarOpen; mobileSidebarOpen = false" class="hidden lg:flex p-2 bg-white/10 hover:bg-white text-white hover:text-primary-light rounded-xl transition-all shadow-sm border border-white/10">
           <i class="bi bi-chevron-bar-left text-xl" x-show="sidebarOpen"></i>
           <i class="bi bi-chevron-bar-right text-xl" x-show="!sidebarOpen"></i>
       </button>
       
       <!-- Close Button (Mobile Only) -->
       <button @click="mobileSidebarOpen = false" class="lg:hidden p-2 bg-white/10 hover:bg-rose-500 text-white rounded-xl transition-all shadow-sm border border-white/10">
           <i class="bi bi-x-lg text-lg"></i>
       </button>
   </div>

   <div class="h-full px-4 pb-4 overflow-y-auto custom-scrollbar">
      <ul class="space-y-2 font-medium">
         <!-- Dashboard -->
         <li>
            <a href="{{ url('administrator/') }}" class="flex items-center p-2.5 text-white rounded-xl hover:bg-white/10 group {{ Request::is('administrator') ? 'bg-white/15 shadow-sm' : '' }}">
               <i class="bi bi-grid-fill w-5 h-5 text-yellow-400 flex items-center justify-center"></i>
               <span class="ms-3 text-sm font-semibold tracking-tight" x-show="sidebarOpen">Dasbor Utama</span>
            </a>
         </li>
         
         <li class="pt-4 mt-4 border-t border-white/20" x-show="sidebarOpen">
            <span class="px-2 text-[10px] font-bold text-yellow-500 uppercase tracking-widest">Manajemen Master</span>
         </li>

         <!-- Data User (Level 1 & 4) -->
         @if(Session::get('level') == "1" || Session::get('level') == "4")
         <li>
            <a href="{{ url('administrator/datauser') }}" class="flex items-center p-2.5 text-white rounded-xl hover:bg-white/10 group {{ Request::is('administrator/datauser*') ? 'bg-white/15' : '' }}">
               <i class="bi bi-people-fill w-5 h-5 text-white flex items-center justify-center"></i>
               <span class="ms-3 text-sm font-semibold tracking-tight" x-show="sidebarOpen">Data Pengguna</span>
            </a>
         </li>
         @endif

         <!-- Data Banjar (Standalone) -->
         <li>
            <a href="{{ url('administrator/databanjar') }}" class="flex items-center p-2.5 text-white rounded-xl hover:bg-white/10 group {{ Request::is('administrator/databanjar*') ? 'bg-white/15' : '' }}">
               <i class="bi bi-houses w-5 h-5 text-white flex items-center justify-center"></i>
               <span class="ms-3 text-sm font-semibold tracking-tight" x-show="sidebarOpen">Data Banjar</span>
            </a>
         </li>

         <!-- Data Pendatang (Bendesa / Kelian Access) -->
         @if(Session::get('level') == "1" || Session::get('level') == "2")
         <li>
            <a href="{{ url('administrator/pendatang') }}" class="flex items-center p-2.5 text-white rounded-xl hover:bg-white/10 group {{ Request::is('administrator/pendatang*') ? 'bg-white/15' : '' }}">
               <i class="bi bi-person-walking w-5 h-5 text-white flex items-center justify-center"></i>
               <span class="ms-3 text-sm font-semibold tracking-tight" x-show="sidebarOpen">Data Pendatang</span>
            </a>
         </li>
         @endif

         <!-- Data Usaha -->
         <li>
            <a href="{{ url('administrator/data_usaha') }}" class="flex items-center p-2.5 text-white rounded-xl hover:bg-white/10 group {{ Request::is('administrator/data_usaha*') ? 'bg-white/15' : '' }}">
               <i class="bi bi-briefcase-fill w-5 h-5 text-white flex items-center justify-center"></i>
               <span class="ms-3 text-sm font-semibold tracking-tight" x-show="sidebarOpen">Data Unit Usaha</span>
            </a>
         </li>

         <!-- Settings Dropdown -->
         <li>
            <button type="button" @click="openSettings = !openSettings" 
                    class="flex items-center w-full p-2.5 text-white transition duration-75 rounded-xl group hover:bg-white/10">
                  <i class="bi bi-gear-fill w-5 h-5 text-white flex items-center justify-center"></i>
                  <span class="flex-1 ms-3 text-left text-sm font-semibold tracking-tight" x-show="sidebarOpen">Pengaturan & Alat</span>
                  <i class="bi bi-chevron-down w-3 h-3 transition-transform duration-300" :class="openSettings ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <ul x-show="openSettings && sidebarOpen" x-transition x-cloak class="py-2 space-y-1 ml-4 border-l border-white/10 pl-2">
                  <li><a href="{{ url('administrator/datamenu') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/datamenu*') ? 'text-white bg-white/5' : '' }}">Menu Anggota</a></li>
                  <li><a href="{{ url('administrator/data_laporan') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/data_laporan*') ? 'text-white bg-white/5' : '' }}">Arsip Laporan</a></li>
                  @if(Session::get('level') == "1" || Session::get('level') == "4")
                  <li><a href="{{ url('administrator/settings') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/settings') ? 'text-white bg-white/5' : '' }}">Pengaturan Website</a></li>
                  <li><a href="{{ route('administrator.settings.payment_gateway') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/settings/payment_gateway*') ? 'text-white bg-white/5' : '' }}">Payment Gateway</a></li>
                  <li><a href="{{ url('administrator/settings/waha') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/settings/waha*') ? 'text-white bg-white/5' : '' }}">WAHA WhatsApp</a></li>
                  <li><a href="{{ url('administrator/settings/api') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/settings/api*') ? 'text-white bg-white/5' : '' }}">API Management</a></li>
                  @endif
                  
            </ul>
         </li>

          <li class="pt-4 mt-4 border-t border-white/20" x-show="sidebarOpen">
            <span class="px-2 text-[10px] font-bold text-yellow-500 uppercase tracking-widest">Data Operasional</span>
          </li>

          <!-- Verifikasi Pembayaran (Standalone) -->
          <li>
            <a href="{{ url('administrator/verifikasi_pembayaran') }}" class="flex items-center p-2.5 text-white rounded-xl hover:bg-white/10 group {{ Request::is('administrator/verifikasi_pembayaran*') ? 'bg-white/15' : '' }}">
               <i class="bi bi-clock-history w-5 h-5 text-white flex items-center justify-center"></i>
               <span class="ms-3 text-sm font-semibold tracking-tight" x-show="sidebarOpen">Verifikasi Pembayaran</span>
               @php
                  $pendingCount = \App\Models\Danapunia::where('metode_pembayaran', 'transfer_manual')
                     ->where('status_verifikasi', 'pending')->count() + 
                     \App\Models\Sumbangan::where('metode_pembayaran', 'transfer_manual')
                     ->where('status_verifikasi', 'pending')->count() +
                     \App\Models\TiketWisata::where('metode_pembayaran', 'transfer_manual')
                     ->where('status_verifikasi', 'pending')->count();
               @endphp
               @if($pendingCount > 0)
               <span class="ms-auto bg-rose-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full" x-show="sidebarOpen">{{ $pendingCount }}</span>
               @endif
            </a>
          </li>

          <!-- Keuangan (Standalone) -->
          <li>
            <a href="{{ url('administrator/keuangan') }}" class="flex items-center p-2.5 text-white rounded-xl hover:bg-white/10 group {{ Request::is('administrator/keuangan*') ? 'bg-white/15' : '' }}">
               <i class="bi bi-bank w-5 h-5 text-yellow-400 flex items-center justify-center"></i>
               <span class="ms-3 text-sm font-semibold tracking-tight" x-show="sidebarOpen">Keuangan</span>
            </a>
          </li>

          <!-- Objek Wisata (Standalone) -->
          @if(Session::get('level') == "1" || Session::get('level') == "2" || Session::get('level') == "4")
          <li>
            <a href="{{ url('administrator/objek_wisata') }}" class="flex items-center p-2.5 text-white rounded-xl hover:bg-white/10 group {{ Request::is('administrator/objek_wisata*') ? 'bg-white/15' : '' }}">
               <i class="bi bi-geo-alt w-5 h-5 text-white flex items-center justify-center"></i>
               <span class="ms-3 text-sm font-semibold tracking-tight" x-show="sidebarOpen">Objek Wisata</span>
            </a>
          </li>
          @endif

          <!-- Ticket Counter Management Dropdown -->
          @if(Session::get('level') == "1" || Session::get('level') == "2" || Session::get('level') == "4")
          <li>
            <button type="button" @click="openTicketCounter = !openTicketCounter" 
                    class="flex items-center w-full p-2.5 text-white transition duration-75 rounded-xl group hover:bg-white/10">
                  <i class="bi bi-qr-code-scan w-5 h-5 text-white flex items-center justify-center"></i>
                  <span class="flex-1 ms-3 text-left text-sm font-semibold tracking-tight" x-show="sidebarOpen">Ticket Counter</span>
                  <i class="bi bi-chevron-down w-3 h-3 transition-transform duration-300" :class="openTicketCounter ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <ul x-show="openTicketCounter && sidebarOpen" x-transition x-cloak class="py-2 space-y-1 ml-4 border-l border-white/10 pl-2">
                  <li><a href="{{ url('administrator/ticket_counter_data') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/ticket_counter_data') ? 'text-white bg-white/5' : '' }}">Dashboard Tiket</a></li>
                  <li><a href="{{ url('administrator/staff_counter') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/staff_counter*') ? 'text-white bg-white/5' : '' }}">Staff Counter</a></li>
                  <li><a href="{{ url('administrator/ticket_counter_data/history') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/ticket_counter_data/history*') ? 'text-white bg-white/5' : '' }}">Riwayat Pembelian</a></li>
            </ul>
          </li>
          @endif

          <!-- Data Blog Dropdown -->
          <li>
            <button type="button" @click="openBlog = !openBlog" 
                    class="flex items-center w-full p-2.5 text-white transition duration-75 rounded-xl group hover:bg-white/10">
                  <i class="bi bi-newspaper w-5 h-5 text-white flex items-center justify-center"></i>
                  <span class="flex-1 ms-3 text-left text-sm font-semibold tracking-tight" x-show="sidebarOpen">Jurnalistik & Berita</span>
                  <i class="bi bi-chevron-down w-3 h-3 transition-transform duration-300" :class="openBlog ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <ul x-show="openBlog && sidebarOpen" x-transition x-cloak class="py-2 space-y-1 ml-4 border-l border-white/10 pl-2">
                  <li><a href="{{ url('administrator/databerita') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/databerita') ? 'text-white bg-white/5' : '' }}">Daftar Berita</a></li>
                  <li><a href="{{ url('administrator/data_kategoriberita') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/data_kategoriberita*') ? 'text-white bg-white/5' : '' }}">Kategori</a></li>
            </ul>
          </li>

          <!-- New Agenda Dropdown -->
          <li>
            <button type="button" @click="openAgenda = !openAgenda" 
                    class="flex items-center w-full p-2.5 text-white transition duration-75 rounded-xl group hover:bg-white/10">
                  <i class="bi bi-calendar-event w-5 h-5 text-white flex items-center justify-center"></i>
                  <span class="flex-1 ms-3 text-left text-sm font-semibold tracking-tight" x-show="sidebarOpen">Agenda Desa Adat</span>
                  <i class="bi bi-chevron-down w-3 h-3 transition-transform duration-300" :class="openAgenda ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <ul x-show="openAgenda && sidebarOpen" x-transition x-cloak class="py-2 space-y-1 ml-4 border-l border-white/10 pl-2">
                  <li><a href="{{ url('administrator/agenda') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/agenda') ? 'text-white bg-white/5' : '' }}">Daftar Agenda</a></li>
                  <li><a href="{{ url('administrator/kategori_agenda') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/kategori_agenda*') ? 'text-white bg-white/5' : '' }}">Kategori Agenda</a></li>
            </ul>
          </li>

          <li>
            <button type="button" @click="openPunia = !openPunia" 
                    class="flex items-center w-full p-2.5 text-white transition duration-75 rounded-xl group hover:bg-white/10">
                  <i class="bi bi-wallet2 w-5 h-5 text-white flex items-center justify-center"></i>
                  <span class="flex-1 ms-3 text-left text-sm font-semibold tracking-tight" x-show="sidebarOpen">Manajemen Punia</span>
                  <i class="bi bi-chevron-down w-3 h-3 transition-transform duration-300" :class="openPunia ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <ul x-show="openPunia && sidebarOpen" x-transition x-cloak class="py-2 space-y-1 ml-4 border-l border-white/10 pl-2">
                  <li><a href="{{ url('administrator/datapunia_wajib') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/datapunia_wajib*') ? 'text-white bg-white/5' : '' }}">Penerimaan Punia</a></li>
                  <li><a href="{{ url('administrator/kategori_punia') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/kategori_punia*') ? 'text-white bg-white/5' : '' }}">Kategori Alokasi</a></li>
                  <li><a href="{{ url('administrator/alokasi_punia') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/alokasi_punia*') ? 'text-white bg-white/5' : '' }}">Data Alokasi Punia</a></li>
            </ul>
          </li>

          <li>
            <button type="button" @click="openDonasi = !openDonasi" 
                    class="flex items-center w-full p-2.5 text-white transition duration-75 rounded-xl group hover:bg-white/10">
                  <i class="bi bi-heart-pulse-fill w-5 h-5 text-white flex items-center justify-center"></i>
                  <span class="flex-1 ms-3 text-left text-sm font-semibold tracking-tight" x-show="sidebarOpen">Manajemen Donasi</span>
                  <i class="bi bi-chevron-down w-3 h-3 transition-transform duration-300" :class="openDonasi ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <ul x-show="openDonasi && sidebarOpen" x-transition x-cloak class="py-2 space-y-1 ml-4 border-l border-white/10 pl-2">
                  <li><a href="{{ url('administrator/datasumbangan') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/datasumbangan*') ? 'text-white bg-white/5' : '' }}">Penerimaan Donasi</a></li>
                  <li><a href="{{ url('administrator/kategori_donasi') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/kategori_donasi*') ? 'text-white bg-white/5' : '' }}">Kategori Program</a></li>
                  <li><a href="{{ url('administrator/program_donasi') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/program_donasi*') ? 'text-white bg-white/5' : '' }}">Data Program</a></li>
            </ul>
          </li>

          <!-- Manajemen Tenaga Kerja Dropdown -->
          <li>
            <button type="button" @click="openTenaga = !openTenaga" 
                    class="flex items-center w-full p-2.5 text-white transition duration-75 rounded-xl group hover:bg-white/10">
                  <i class="bi bi-person-workspace w-5 h-5 text-white flex items-center justify-center"></i>
                  <span class="flex-1 ms-3 text-left text-sm font-semibold tracking-tight" x-show="sidebarOpen">Manajemen Tenaga Kerja</span>
                  <i class="bi bi-chevron-down w-3 h-3 transition-transform duration-300" :class="openTenaga ? 'rotate-180' : ''" x-show="sidebarOpen"></i>
            </button>
            <ul x-show="openTenaga && sidebarOpen" x-transition x-cloak class="py-2 space-y-1 ml-4 border-l border-white/10 pl-2">
                  <li><a href="{{ url('administrator/data_tenagakerja_aktif') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/data_tenagakerja_aktif*') ? 'text-white bg-white/5' : '' }}">Tenaga Kerja Aktif</a></li>
                  <li><a href="{{ url('administrator/data_tenagakerja_skill') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/data_tenagakerja_skill*') ? 'text-white bg-white/5' : '' }}">Kategori Keahlian</a></li>
                  <li><a href="{{ url('administrator/data_loker') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/data_loker*') ? 'text-white bg-white/5' : '' }}">Lowongan Pekerjaan</a></li>
                  <li><a href="{{ url('administrator/data_tenagakerja_interview') }}" class="flex items-center w-full p-2 text-white/70 hover:text-white transition duration-75 rounded-lg text-xs font-semibold hover:bg-white/5 {{ Request::is('administrator/data_tenagakerja_interview*') ? 'text-white bg-white/5' : '' }}">Wawancara</a></li>
            </ul>
          </li>
          
          <li class="pt-4 mt-4 border-t border-white/20"></li>

          <li>
            <form method="POST" action="{{ url('logoutadmin') }}">
               @csrf
               <a href="{{ url('logoutadmin') }}" onclick="event.preventDefault(); this.closest('form').submit();" 
                  class="flex items-center p-2.5 text-white rounded-xl hover:bg-rose-500/20 group hover:text-rose-100 transition-all">
                  <i class="bi bi-box-arrow-right w-5 h-5 flex items-center justify-center"></i>
                  <span class="flex-1 ms-3 whitespace-nowrap text-sm font-semibold tracking-tight" x-show="sidebarOpen">Keluar Sistem</span>
               </a>
            </form>
          </li>
      </ul>
   </div>
</aside>