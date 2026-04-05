@extends('index')

@section('isi_menu')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-center gap-5">
            <div class="h-16 w-16 rounded-2xl bg-primary-light text-white flex items-center justify-center shadow-xl shadow-blue-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight mb-0.5">API Activity Log</h1>
                <p class="text-slate-500 font-semibold text-sm">Monitor penggunaan API oleh pihak ketiga.</p>
            </div>
        </div>
        <a href="{{ url('administrator/settings/api') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-200 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    {{-- Filter --}}
    <div class="glass-card bg-white p-4 shadow-sm">
        <form action="" method="GET" class="flex items-center gap-3">
            <select name="id_api_token" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 outline-none">
                <option value="">Semua Token</option>
                @foreach($tokens as $token)
                    <option value="{{ $token->id_api_token }}" {{ request('id_api_token') == $token->id_api_token ? 'selected' : '' }}>{{ $token->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition-all">Filter</button>
        </form>
    </div>

    {{-- Log Table --}}
    <div class="glass-card bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Waktu</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Token</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Method</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Endpoint</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">IP</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Status</th>
                        <th class="text-left px-5 py-3 font-black text-slate-400 uppercase tracking-wider text-[10px]">Response Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-3 text-slate-600 font-medium whitespace-nowrap">{{ $log->created_at->format('d M Y H:i:s') }}</td>
                            <td class="px-5 py-3">
                                @if($log->apiToken)
                                    <span class="text-slate-700 font-bold">{{ $log->apiToken->name }}</span>
                                @else
                                    <span class="text-slate-400 italic">Dihapus</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-600 rounded font-bold text-[10px]">{{ $log->method }}</span>
                            </td>
                            <td class="px-5 py-3 font-mono text-slate-700 font-medium">{{ $log->endpoint }}</td>
                            <td class="px-5 py-3 text-slate-500 font-mono">{{ $log->ip_address }}</td>
                            <td class="px-5 py-3">
                                @if($log->response_code >= 200 && $log->response_code < 300)
                                    <span class="px-1.5 py-0.5 bg-emerald-50 text-emerald-600 rounded font-bold text-[10px]">{{ $log->response_code }}</span>
                                @elseif($log->response_code >= 400)
                                    <span class="px-1.5 py-0.5 bg-red-50 text-red-600 rounded font-bold text-[10px]">{{ $log->response_code }}</span>
                                @else
                                    <span class="px-1.5 py-0.5 bg-amber-50 text-amber-600 rounded font-bold text-[10px]">{{ $log->response_code }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-slate-500">{{ $log->response_time_ms }}ms</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-sm font-semibold text-slate-400">
                                Belum ada activity log.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
