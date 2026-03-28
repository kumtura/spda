<div class="flex items-center gap-2 sm:gap-6">
    <!-- Global Search -->
    <div class="hidden md:block relative group">
        <label for="search" class="sr-only">Cari...</label>
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors group-focus-within:text-primary-light text-slate-400">
            <i class="bi bi-search text-xs"></i>
        </div>
        <input type="search" name="search" id="search"
               class="block w-64 bg-slate-100/50 border border-transparent rounded-xl py-2 pl-11 pr-4 text-xs font-semibold placeholder-slate-400 focus:ring-4 focus:ring-primary-light/5 focus:bg-white focus:border-primary-light/20 transition-all outline-none"
               placeholder="Cari data atau modul...">
    </div>

    <!-- User Profile Dropdown -->
    <div class="relative" x-data="{ open: false }" @click.away="open = false">
        <button @click="open = !open" 
                class="flex items-center gap-3 p-1 rounded-xl hover:bg-slate-100 transition-all group">
            <div class="relative">
                <div class="h-9 w-9 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center text-primary-light font-black text-xs overflow-hidden">
                    <img class="h-full w-full object-cover transition-transform group-hover:scale-110" 
                         src="{{ asset('storage/assets/src/assets/images/users/profile-pic.jpg') }}" 
                         alt="Profile">
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-emerald-500 border-2 border-white rounded-full"></div>
            </div>
            <div class="hidden sm:block text-left">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Administrator</p>
                <div class="flex items-center gap-1">
                    <span class="text-xs font-bold text-slate-700">{{ Session::get('namapt') }}</span>
                    <i class="bi bi-chevron-down text-[10px] text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </div>
            </div>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-95"
             class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-xl overflow-hidden shadow-xl z-50 py-1.5 origin-top-right"
             x-cloak>
            
            <div class="px-4 py-3 border-b border-slate-100 mb-1">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1.5">Detail Akun</p>
                <p class="text-xs font-bold text-slate-800 truncate capitalize">{{ Session::get('namapt') }}</p>
            </div>

            <a href="{{ url('administrator/userprofile') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-blue-50 hover:text-primary-light transition-colors mx-1.5 rounded-lg">
                <i class="bi bi-person-circle text-lg"></i>
                Profil Saya
            </a>

            @if(in_array(Session::get('level'), [1, 4]))
            <a href="{{ route('administrator.settings.payment_gateway') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-blue-50 hover:text-primary-light transition-colors mx-1.5 rounded-lg">
                <i class="bi bi-credit-card-2-front text-lg"></i>
                Payment Gateway
            </a>

            <a href="{{ url('administrator/settings') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-blue-50 hover:text-primary-light transition-colors mx-1.5 rounded-lg">
                <i class="bi bi-gear text-lg"></i>
                Pengaturan Website
            </a>
            @endif
            
            <div class="h-px bg-slate-100 my-1 mx-3"></div>

            <a href="{{ url('logoutadmin/') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors mx-1.5 rounded-lg">
                <i class="bi bi-power text-lg"></i>
                Keluar Sistem
            </a>
        </div>
    </div>
</div>
