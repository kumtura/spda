<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
    <title>SPDA - Mobile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
    <div class="mobile-container overflow-x-hidden">
        @yield('content')

        <!-- Bottom Navigation (Minimalist KitaBisa Style) -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white border-t border-gray-100 flex justify-around items-center h-[70px] z-50 px-2 pb-[env(safe-area-inset-bottom)]">
            <a href="{{ url('administrator/') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                <i class="bi bi-house-door{{ Request::is('administrator') ? '-fill' : '' }} text-xl"></i>
                <span class="text-[10px] font-semibold">Home</span>
            </a>
            
            @if(Session::get('level') == "3")
                <!-- Business Unit Features -->
                <a href="{{ url('administrator/usaha/iuran') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/usaha/iuran*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-wallet2 text-xl"></i>
                    <span class="text-[10px] font-semibold">Iuran</span>
                </a>
                <a href="{{ url('administrator/usaha/loker') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/usaha/loker*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-plus-circle text-xl"></i>
                    <span class="text-[10px] font-semibold">Loker</span>
                </a>
            @endif

            @if(Session::get('level') == "2")
                <!-- Kelian Adat Features -->
                <a href="{{ url('administrator/data_usaha') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/data_usaha*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-briefcase text-xl"></i>
                    <span class="text-[10px] font-semibold">Usaha</span>
                </a>
                <a href="{{ url('administrator/datauser') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/datauser*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                    <i class="bi bi-people text-xl"></i>
                    <span class="text-[10px] font-semibold">User</span>
                </a>
            @endif

            <a href="{{ url('administrator/userprofile') }}" class="flex flex-col items-center justify-center w-full h-full space-y-1 {{ Request::is('administrator/userprofile*') ? 'text-[#00a6eb]' : 'text-gray-400' }}">
                <i class="bi bi-person{{ Request::is('administrator/userprofile*') ? '-fill' : '' }} text-xl"></i>
                <span class="text-[10px] font-semibold">Profil</span>
            </a>
        </nav>
    </div>
</body>
</html>
