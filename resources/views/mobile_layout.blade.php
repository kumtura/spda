<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
    <title>SPDA - Mobile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #ffffff; margin: 0; font-family: 'Inter', sans-serif;}
        .mobile-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: #ffffff;
            min-height: 100vh;
            position: relative;
            padding-bottom: 80px;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased font-sans text-gray-900 bg-white">
    <div class="mobile-container overflow-x-hidden pt-16">
        <!-- Branded Header Mobile -->
        <header class="fixed top-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] h-16 bg-[#00a6eb] flex items-center justify-between px-6 z-50 shadow-md">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center p-1.5 shadow-sm shrink-0">
                    @if(file_exists(public_path('storage/logos/logo.png')))
                        <img src="{{ asset('storage/logos/logo.png') }}" class="h-full w-full object-contain" alt="Logo">
                    @else
                        <i class="bi bi-grid-1x2-fill text-[#00a6eb] text-xl"></i>
                    @endif
                </div>
                <div class="flex flex-col">
                    <h1 class="text-white font-black text-sm tracking-tight leading-none uppercase">{{ $village['name'] ?? 'SPDA' }}</h1>
                    <p class="text-white/70 font-bold text-[9px] uppercase tracking-widest leading-none mt-1">Desa Adat Terpadu</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @php
                    $roleLabel = Session::get('level') == '2' ? 'Kelian' : (Session::get('level') == '3' ? 'Usaha' : 'Admin');
                @endphp
                <span class="bg-white/20 backdrop-blur-md text-white text-[9px] font-bold px-3 py-1.5 rounded-full uppercase tracking-widest">{{ $roleLabel }}</span>
            </div>
        </header>

        @yield('isi_menu')

        <!-- Bottom Navigation (Minimalist KitaBisa Style) -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white border-t border-gray-100 flex justify-around items-center h-[70px] z-50 px-2 pb-[env(safe-area-inset-bottom)]">
            @php
                $homeActiveColor = 'text-[#00a6eb]';
            @endphp
            @if(Session::get('level') != "2")
            <a href="{{ url('administrator/') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator') ? $homeActiveColor : 'text-gray-400' }}">
                <i class="bi bi-house-door{{ Request::is('administrator') ? '-fill' : '' }} text-xl"></i>
                <span class="text-[10px] font-semibold">Home</span>
            </a>
            @endif
            
            @if(Session::get('level') == "3")
                <!-- Business Unit Features -->
                <a href="{{ url('administrator/usaha/punia') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/usaha/punia*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-wallet2 text-xl"></i>
                    <span class="text-[10px] font-semibold">Punia</span>
                </a>
                <a href="{{ url('administrator/usaha/loker') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/usaha/loker*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-people text-xl"></i>
                    <span class="text-[10px] font-semibold">Tenaga Kerja</span>
                </a>
            @endif

            @if(Session::get('level') == "2")
                <!-- Kelian Adat Features -->
                <a href="{{ url('administrator/') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator') && !Request::is('administrator/*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-house-door{{ Request::is('administrator') && !Request::is('administrator/*') ? '-fill' : '' }} text-xl"></i>
                    <span class="text-[10px] font-semibold">Home</span>
                </a>
                <a href="{{ url('administrator/kelian/punia') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/kelian/punia*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-wallet2 text-xl"></i>
                    <span class="text-[10px] font-semibold">Punia</span>
                </a>
                <a href="{{ url('administrator/kelian/tiket') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/kelian/tiket*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-ticket-perforated text-xl"></i>
                    <span class="text-[10px] font-semibold">Tiket</span>
                </a>
                <a href="{{ url('administrator/kelian/pendatang') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/kelian/pendatang*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-people text-xl"></i>
                    <span class="text-[10px] font-semibold">Pendatang</span>
                </a>
            @endif

            @php
                $profileActiveColor = 'text-[#00a6eb]';
            @endphp
            <a href="{{ url('administrator/userprofile') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/userprofile*') ? $profileActiveColor : 'text-gray-400' }}">
                <i class="bi bi-person{{ Request::is('administrator/userprofile*') ? '-fill' : '' }} text-xl"></i>
                <span class="text-[10px] font-semibold">Profil</span>
            </a>
        </nav>
    </div>
    
    @stack('scripts')
</body>
</html>
