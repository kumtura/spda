<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
    <title>SPDA - Sistem Punia Desa Adat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #f3f4f6; padding: 0; margin: 0; font-family: 'Inter', sans-serif;}
        .mobile-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: #ffffff;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            padding-bottom: 70px; /* Space for bottom nav */
        }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased font-sans bg-gray-100">
    <div class="mobile-container overflow-x-hidden pt-12">
        <!-- Branded Header Mobile Public -->
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
            <div class="h-10 w-10 bg-white/20 rounded-full flex items-center justify-center text-white">
                <i class="bi bi-info-circle-fill"></i>
            </div>
        </header>

        @yield('content')

        <!-- Public Bottom Navigation -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white border-t border-gray-100 flex justify-around items-center h-[65px] shadow-[0_-4px_20px_-15px_rgba(0,0,0,0.1)] z-50 px-2 pb-safe">
            <a href="{{ route('public.home') }}" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 {{ request()->routeIs('public.home') ? 'text-[#00a6eb]' : 'text-slate-400' }} transition-colors">
                <i class="bi bi-house-door text-xl"></i>
                <span class="text-[9px] font-medium tracking-wide">Home</span>
            </a>
            <a href="{{ route('public.punia') }}" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 {{ request()->routeIs('public.punia') ? 'text-[#00a6eb]' : 'text-slate-400' }} transition-colors">
                <i class="bi bi-envelope-heart text-xl"></i>
                <span class="text-[9px] font-medium tracking-wide">Punia</span>
            </a>
            <a href="{{ route('public.berita') }}" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 {{ request()->routeIs('public.berita*') ? 'text-[#00a6eb]' : 'text-slate-400' }} transition-colors">
                <i class="bi bi-journal-text text-xl"></i>
                <span class="text-[9px] font-medium tracking-wide">Berita</span>
            </a>
            <a href="{{ route('public.donasi') }}" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 {{ request()->routeIs('public.donasi') ? 'text-[#00a6eb]' : 'text-slate-400' }} transition-colors">
                <i class="bi bi-heart text-xl"></i>
                <span class="text-[9px] font-medium tracking-wide">Donasi</span>
            </a>
            <a href="{{ route('public.unit_usaha') }}" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 mt-0.5 {{ request()->routeIs('public.unit_usaha*') ? 'text-[#00a6eb]' : 'text-slate-400 hover:text-[#00a6eb]' }} transition-colors">
                <i class="bi bi-shop text-xl mb-0.5"></i>
                <span class="text-[9px] font-medium tracking-wide">Unit Usaha</span>
            </a>
        </nav>

    </div>
</body>
</html>
