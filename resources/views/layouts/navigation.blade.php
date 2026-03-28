<nav x-data="{ open: false, userOpen: false }" class="fixed top-0 z-50 w-full border-b border-white/10" style="background:linear-gradient(45deg, #044c92 0 50%, #04488b 50% 100%);">
    <div class="px-4 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <!-- Sidebar Toggle (Mobile) -->
                <button @click="sidebarOpen = !sidebarOpen" type="button" class="inline-flex items-center p-2 text-sm text-blue-200 rounded-lg sm:hidden hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                
                <!-- Logo & Brand -->
                <a href="{{ route('administrator.dashboard') }}" class="flex ms-2 md:me-24">
                    <img src="{{ url('storage/login_bg/donasi.png') }}" class="h-10 me-3" alt="Logo" />
                    <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-yellow-400">DANA PUNIA</span>
                </a>

                <!-- Search (Desktop) -->
                <div class="hidden md:block ms-6">
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-blue-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                        </div>
                        <input type="search" class="block w-64 p-2 ps-10 text-sm text-white border border-white/20 rounded-lg bg-white/10 focus:ring-blue-500 focus:border-blue-500 placeholder-blue-200" placeholder="Search...">
                    </div>
                </div>
            </div>

            <div class="flex items-center">
                <!-- Notifications -->
                <div class="flex items-center ms-3 me-3">
                    <button type="button" class="relative p-2 text-blue-100 rounded-lg hover:bg-white/10">
                        <span class="sr-only">View notifications</span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <div class="absolute inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-1 -end-1">5</div>
                    </button>
                </div>

                <!-- User Menu -->
                <div class="flex items-center ms-3">
                    <div>
                        <button type="button" @click="userOpen = !userOpen" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-blue-300" aria-expanded="false">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-10 h-10 rounded-full border-2 border-white" src="{{ url('storage/assets/src/assets/images/users/profile-pic.jpg') }}" alt="user photo">
                        </button>
                    </div>
                    <div x-show="userOpen" @click.away="userOpen = false" x-transition class="absolute top-12 right-4 z-50 text-base list-none bg-white divide-y divide-gray-100 rounded shadow-lg dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 dark:text-white" role="none">
                                Hello, {{ Session::get('namapt') }}
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300" role="none">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li><a href="{{ url('administrator/userprofile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">My Profile</a></li>
                            @if(in_array(Session::get('level'), [1, 4]))
                            <li><a href="{{ route('administrator.settings.payment_gateway') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Payment Gateway</a></li>
                            @endif
                            <li><a href="{{ url('administrator/databerita') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">View Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
