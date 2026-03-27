<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50/50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS / JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js (if not handled by Vite) -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Standard CDNs for charts and extras used in pages -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- CKEditor -->
    <script src="https://cdn.ckeditor.com/4.25.0-lts/standard/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/4.25.0-lts/adapters/jquery.js"></script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    <div class="min-h-screen flex flex-col lg:flex-row">
        
        <!-- Sidebar Navigation -->
        @include('admin.global.leftmenu')

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 bg-slate-50 transition-all duration-300"
              :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">
            
            <!-- Top Header -->
            <header class="fixed top-0 right-0 left-0 z-30 h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-8 transition-all duration-300 shadow-sm"
                    :class="sidebarOpen ? 'lg:left-64' : 'lg:left-20'">
                <div class="flex items-center gap-4">
                    <!-- Mobile Menu Toggle -->
                    <button type="button" @click="mobileSidebarOpen = true" class="lg:hidden p-2 -ml-2 text-slate-500 hover:bg-slate-100/80 rounded-xl transition-all">
                        <i class="bi bi-list text-2xl leading-none"></i>
                    </button>
                    
                    <div class="hidden sm:block text-left">
                        <h2 class="text-[10px] font-bold text-primary-light/60 uppercase tracking-[0.2em] mb-0.5">Panel Administrasi</h2>
                        <h1 class="text-lg font-black text-slate-800 tracking-tight uppercase">{{ $village['name'] ?? 'SPDA' }}</h1>
                    </div>
                </div>

                @include('admin.global.menu')
            </header>

            <!-- Halaman Konten -->
            <div class="flex-1 p-4 sm:p-8 mt-20 min-h-[calc(100vh-5rem)]">
                <div class="max-w-7xl mx-auto">
                    @yield('isi_menu')
                </div>
            </div>

            <footer class="mt-auto px-8 py-6 border-t bg-white/50 text-center">
                <p class="text-sm text-slate-400 font-bold tracking-wide">
                    &copy; {{ date('Y') }} <span class="text-slate-600">{{ $village['name'] ?? 'SPDA' }}</span>. 
                    <span class="hidden sm:inline">Dikelola dengan presisi untuk keunggulan.</span>
                </p>
            </footer>
        </main>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="mobileSidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 lg:hidden bg-slate-900/60"
         @click="mobileSidebarOpen = false"
         x-cloak>
    </div>
</body>
</html>
