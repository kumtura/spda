<aside id="sidebar" 
    x-data="{ 
        openSettings: false, 
        openGambar: false, 
        openGbrHome: false,
        openBlog: false,
        openTenaga: false
    }"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" 
    aria-label="Sidebar" 
    style="background:linear-gradient(45deg, #044c92 0 50%, #04488b 50% 100%);">
   
   <div class="h-full px-3 pb-4 overflow-y-auto custom-scrollbar">
      <ul class="space-y-2 font-medium">
         <!-- Dashboard -->
         <li>
            <a href="{{ route('administrator.dashboard') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 group {{ request()->routeIs('administrator.dashboard') ? 'bg-white/10' : '' }}">
               <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>
         
         <li class="pt-4 mt-4 border-t border-white/20">
            <span class="px-2 text-xs font-semibold text-yellow-500 uppercase tracking-wider">Master</span>
         </li>

         <!-- Data User (Level 1) -->
         @if(Session::get('level') == "1" || Session::get('level') == "4")
         <li>
            <a href="{{ url('administrator/datauser') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 group">
               <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
               <span class="ms-3">Data User</span>
            </a>
         </li>
         <li>
            <a href="{{ url('administrator/staff_counter') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 group">
               <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.005 3.005 0 013.75-2.906z"></path></svg>
               <span class="ms-3">Staff Counter</span>
            </a>
         </li>
         @endif

         <!-- Settings Dropdown -->
         <li>
            <button type="button" @click="openSettings = !openSettings" class="flex items-center w-full p-2 text-base text-white transition duration-75 rounded-lg group hover:bg-white/10">
                  <svg class="flex-shrink-0 w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                  <span class="flex-1 ms-3 text-left">Settings</span>
                  <svg class="w-3 h-3 transition-transform" :class="openSettings ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
            </button>
            <ul x-show="openSettings" x-transition class="py-2 space-y-2">
                  <li><a href="{{ url('administrator/databanjar') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10">Data Banjar</a></li>
                  <li><a href="#" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10">Data General</a></li>
                  <li><a href="{{ url('administrator/datamenu') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10">Data Member Menu</a></li>
                  <li><a href="#" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10 uppercase text-[10px] font-bold tracking-widest opacity-30 mt-4 px-2">Integrasi</a></li>
                  <li><a href="{{ url('administrator/settings/payment_gateway') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10">Payment Gateway</a></li>
                  <li><a href="{{ url('administrator/settings/api') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10">API Management</a></li>
                  
                  <!-- Data Gambar Nest -->
                  <li>
                    <button type="button" @click.stop="openGambar = !openGambar" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10 text-sm">
                        <span>Data Gambar</span>
                        <svg class="w-3 h-3 ms-auto transition-transform" :class="openGambar ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
                    </button>
                    <ul x-show="openGambar" x-transition class="py-2 space-y-2">
                        <li>
                            <button type="button" @click.stop="openGbrHome = !openGbrHome" class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-16 group hover:bg-white/10 text-xs text-blue-200">
                                <span>Gbr HomePage</span>
                                <svg class="w-2 h-2 ms-auto transition-transform" :class="openGbrHome ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
                            </button>
                            <ul x-show="openGbrHome" x-transition class="py-1 space-y-1">
                                <li><a href="{{ url('administrator/datakategori_slides') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-20 group hover:bg-white/10 text-xs">Kategori</a></li>
                                <li><a href="{{ url('administrator/datagambar_slides') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-20 group hover:bg-white/10 text-xs">Data Gambar</a></li>
                            </ul>
                        </li>
                    </ul>
                  </li>
            </ul>
         </li>

         <li class="pt-4 mt-4 border-t border-white/20">
            <span class="px-2 text-xs font-semibold text-yellow-500 uppercase tracking-wider">Secondary Master</span>
         </li>

         <!-- Data Usaha -->
         <li>
            <a href="{{ url('administrator/data_usaha') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 group">
               <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"></path></svg>
               <span class="ms-3">Data Usaha</span>
            </a>
         </li>

         <!-- Data Blog Dropdown -->
         <li>
            <button type="button" @click="openBlog = !openBlog" class="flex items-center w-full p-2 text-base text-white transition duration-75 rounded-lg group hover:bg-white/10">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v10a1 1 0 01-1 1H2a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg>
                  <span class="flex-1 ms-3 text-left">Data Blog</span>
                  <svg class="w-3 h-3 transition-transform" :class="openBlog ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
            </button>
            <ul x-show="openBlog" x-transition class="py-2 space-y-2">
                  <li><a href="{{ url('administrator/databerita') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10">Data Blog</a></li>
                  <li><a href="{{ url('administrator/data_kategoriberita') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10">Kategori Blog</a></li>
            </ul>
         </li>

         <li>
            <a href="{{ url('administrator/datapunia_wajib') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 group">
               <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
               <span class="ms-3">Punia Wajib</span>
            </a>
         </li>

         <li>
            <a href="{{ url('administrator/datasumbangan') }}" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 group">
               <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
               <span class="ms-3">Data Sumbangan</span>
            </a>
         </li>

         <!-- Tenaga Kerja Dropdown -->
         <li>
            <button type="button" @click="openTenaga = !openTenaga" class="flex items-center w-full p-2 text-base text-white transition duration-75 rounded-lg group hover:bg-white/10">
                  <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3.005 3.005 0 013.75-2.906z"></path></svg>
                  <span class="flex-1 ms-3 text-left">Tenaga Kerja</span>
                  <svg class="w-3 h-3 transition-transform" :class="openTenaga ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
            </button>
            <ul x-show="openTenaga" x-transition class="py-2 space-y-2">
                  <li><a href="{{ url('administrator/data_tenagakerja') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10 text-sm">Data Pekerja</a></li>
                  <li><a href="{{ url('administrator/data_tenagakerja_skill') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10 text-sm">Skill Pekerja</a></li>
                  <li><a href="{{ url('administrator/data_tenagakerja_interview') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10 text-sm">Interview</a></li>
                  <li><a href="{{ url('administrator/data_tenagakerja_approve') }}" class="flex items-center w-full p-2 text-white transition duration-75 rounded-lg pl-11 group hover:bg-white/10 text-sm">Diterima</a></li>
            </ul>
         </li>

         <li class="pt-4 mt-4 border-t border-white/20"></li>

         <li>
            <form method="POST" action="{{ route('logout') }}">
               @csrf
               <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center p-2 text-white rounded-lg hover:bg-white/10 group">
                  <svg class="flex-shrink-0 w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 18 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/></svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Log Out</span>
               </a>
            </form>
         </li>
      </ul>
   </div>
</aside>
