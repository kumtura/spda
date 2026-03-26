<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
    <title>GoKumtura - Layanan Publik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
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
    <div class="mobile-container overflow-x-hidden">
        @yield('content')

        <!-- Public Bottom Navigation -->
        <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-[480px] bg-white border-t border-gray-100 flex justify-around items-center h-[65px] shadow-[0_-4px_20px_-15px_rgba(0,0,0,0.1)] z-50 px-2 pb-safe">
            <a href="{{ url('/') }}" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 text-[#00a6eb] transition-colors">
                <i class="bi bi-house-door text-xl"></i>
                <span class="text-[9px] font-medium tracking-wide">Home</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 text-slate-400 hover:text-[#00a6eb] transition-colors">
                <i class="bi bi-envelope-heart text-xl"></i>
                <span class="text-[9px] font-medium tracking-wide">Punia</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 text-slate-400 hover:text-[#00a6eb] transition-colors">
                <i class="bi bi-journal-text text-xl"></i>
                <span class="text-[9px] font-medium tracking-wide">Blog</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 text-slate-400 hover:text-[#00a6eb] transition-colors">
                <i class="bi bi-heart text-xl"></i>
                <span class="text-[9px] font-medium tracking-wide">Donasi</span>
            </a>
            <a href="{{ url('/login') }}" class="flex flex-col items-center justify-center w-full h-full space-y-0.5 text-slate-400 hover:text-[#00a6eb] transition-colors">
                <i class="bi bi-person text-xl"></i>
                <span class="text-[9px] font-medium tracking-wide">Login</span>
            </a>
        </nav>
    </div>
</body>
</html>
