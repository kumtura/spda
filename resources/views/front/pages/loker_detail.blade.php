@extends('mobile_layout_public')

@section('content')
<div class="bg-white px-4 pt-6 pb-24 space-y-6">

    <!-- Back -->
    <a href="{{ route('public.home') }}" class="inline-flex items-center gap-1 text-slate-400 hover:text-[#00a6eb] text-xs font-medium transition-colors">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>

    <!-- Company Header -->
    <div class="flex items-start gap-4">
        <div class="h-16 w-16 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center shrink-0 overflow-hidden">
            @if($loker->usaha && $loker->usaha->detail && $loker->usaha->detail->logo)
                @php
                    $logoPath = file_exists(public_path('usaha/icon/'.$loker->usaha->detail->logo)) 
                        ? 'usaha/icon/'.$loker->usaha->detail->logo 
                        : 'storage/usaha/icon/'.$loker->usaha->detail->logo;
                @endphp
                <img src="{{ asset($logoPath) }}" class="h-full w-full object-cover" alt="Logo">
            @else
                <i class="bi bi-building text-slate-300 text-2xl"></i>
            @endif
        </div>
        <div class="flex-1">
            <h1 class="text-lg font-bold text-slate-800 leading-tight mb-1">{{ $loker->judul }}</h1>
            <p class="text-xs text-slate-500 mb-2">{{ $loker->usaha->detail->nama_usaha ?? 'Unit Usaha' }}</p>
            <div class="flex items-center gap-2 text-[10px] text-slate-400">
                @if($loker->usaha && $loker->usaha->kategori)
                <span class="flex items-center gap-1">
                    <i class="bi bi-tag text-[9px]"></i>
                    {{ $loker->usaha->kategori->nama_kategori_usaha }}
                </span>
                <span>•</span>
                @endif
                <span class="flex items-center gap-1">
                    <i class="bi bi-clock text-[9px]"></i>
                    {{ \Carbon\Carbon::parse($loker->created_at)->diffForHumans() }}
                </span>
            </div>
        </div>
    </div>

    <!-- Job Description -->
    @if($loker->deskripsi)
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Deskripsi Pekerjaan</h3>
        <div class="prose prose-sm prose-slate max-w-none text-slate-600 leading-relaxed">
            <p class="whitespace-pre-line text-xs">{{ $loker->deskripsi }}</p>
        </div>
    </div>
    @endif

    <!-- Company Info -->
    @if($loker->usaha && $loker->usaha->detail)
    <div class="bg-slate-50 rounded-xl border border-slate-100 p-4">
        <h3 class="text-sm font-bold text-slate-800 mb-3">Tentang Perusahaan</h3>
        <div class="space-y-2 text-xs text-slate-600">
            @if($loker->usaha->detail->alamat_banjar)
            <div class="flex items-start gap-2">
                <i class="bi bi-geo-alt text-slate-400 text-sm shrink-0 mt-0.5"></i>
                <span>{{ $loker->usaha->detail->alamat_banjar }}</span>
            </div>
            @endif
            @if($loker->usaha->detail->no_telp)
            <div class="flex items-center gap-2">
                <i class="bi bi-telephone text-slate-400 text-sm shrink-0"></i>
                <span>{{ $loker->usaha->detail->no_telp }}</span>
            </div>
            @endif
            @if($loker->usaha->detail->email_usaha)
            <div class="flex items-center gap-2">
                <i class="bi bi-envelope text-slate-400 text-sm shrink-0"></i>
                <span>{{ $loker->usaha->detail->email_usaha }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Other Jobs -->
    @if($other_lokers->count() > 0)
    <div>
        <h3 class="text-sm font-bold text-slate-800 mb-3">Lowongan Lainnya</h3>
        <div class="space-y-2">
            @foreach($other_lokers as $other)
                <a href="{{ route('public.loker.detail', $other->id_loker) }}" class="block bg-white rounded-lg border border-slate-100 p-3 hover:border-slate-200 transition-colors">
                    <h4 class="text-xs font-bold text-slate-800 mb-1">{{ $other->judul }}</h4>
                    <p class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($other->created_at)->diffForHumans() }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Apply Button -->
    <div class="fixed bottom-[75px] left-1/2 -translate-x-1/2 w-full max-w-[480px] px-4 z-40">
        <a href="{{ route('login') }}" class="block w-full bg-[#00a6eb] hover:bg-[#0090d0] text-white py-3.5 rounded-xl font-bold text-xs text-center shadow-lg transition-colors">
            Lamar Pekerjaan
        </a>
    </div>
</div>
@endsection
