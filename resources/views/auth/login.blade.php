<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">
    <title>Masuk - SPDA</title>
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
        }
    </style>
</head>
<body class="antialiased font-sans text-gray-900">
    <div class="mobile-container flex flex-col">
        
        <!-- Header KitaBisa Style -->
        <div class="bg-[#00a6eb] w-full h-14 flex items-center px-4 sticky top-0 z-50">
            <a href="{{ url('/') }}" class="text-white mr-4">
                <i class="bi bi-arrow-left text-xl"></i>
            </a>
            <h1 class="text-white font-bold text-base">Masuk</h1>
        </div>

        <div class="px-6 py-8 flex-1">
            <h2 class="text-lg font-bold text-slate-800 mb-8 text-center tracking-tight">
                {{ $village['name'] ?? 'SPDA' }}<br>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mt-2">Manajemen Desa Adat Terpadu</span>
            </h2>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-1">
                    <input id="email" 
                           class="block w-full border-0 border-b border-slate-200 focus:ring-0 focus:border-[#00a6eb] px-1 py-3 text-sm placeholder-slate-400 text-slate-800 transition-colors bg-transparent" 
                           type="email" name="email" :value="old('email')" 
                           placeholder="Alamat Email"
                           required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1" />
                </div>

                <!-- Password -->
                <div class="space-y-1 pt-2">
                    <input id="password" 
                           class="block w-full border-0 border-b border-slate-200 focus:ring-0 focus:border-[#00a6eb] px-1 py-3 text-sm placeholder-slate-400 text-slate-800 transition-colors bg-transparent"
                           type="password"
                           name="password"
                           placeholder="Kata Sandi"
                           required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <div class="flex justify-between items-center pt-2">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="w-4 h-4 text-[#00a6eb] bg-gray-100 border-gray-300 rounded focus:ring-[#00a6eb] focus:ring-2" name="remember">
                        <label for="remember_me" class="ms-2 text-xs font-medium text-slate-600">Ingat saya</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a class="text-xs text-[#00a6eb] hover:underline" href="{{ route('password.request') }}">
                            Lupa sandi?
                        </a>
                    @endif
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-4 px-4 bg-[#00a6eb] text-white font-bold text-sm tracking-wide rounded-xl transition-all shadow-md shadow-[#00a6eb]/20 hover:bg-[#0095d4] focus:ring-4 focus:ring-[#00a6eb]/30">
                        Masuk ke Sistem
                    </button>
                </div>
            </form>
            
            <div class="mt-12 text-center">
                <!-- Teks instruksi kecil / hak cipta -->
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                    &copy; {{ date('Y') }} SPDA - Manajemen Desa Adat Terpadu
                </p>
            </div>
        </div>
    </div>
</body>
</html>
