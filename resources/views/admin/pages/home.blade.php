<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard Home</h2>
                <p class="text-gray-600">Overview of Dana Punia status</p>
                <div class="h-1 w-20 bg-blue-600 mt-2 rounded-full"></div>
            </div>

            <!-- Stats Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Investor -->
                <div class="bg-blue-50 border border-blue-100 p-6 rounded-2xl shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-blue-600 rounded-lg text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                    <h5 class="text-gray-600 font-medium">Total Investor</h5>
                    <p class="text-3xl font-bold text-blue-900 mt-1">{{ count($usaha) }}</p>
                    <a href="{{ url('administrator/data_usaha') }}" class="inline-flex items-center mt-4 text-sm font-semibold text-blue-600 hover:text-blue-800">
                        Cek Detail
                        <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Total Tenaga Kerja -->
                <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-2xl shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-indigo-600 rounded-lg text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <h5 class="text-gray-600 font-medium">Total Tenaga Kerja</h5>
                    <p class="text-3xl font-bold text-indigo-900 mt-1">{{ $jml_karyawan }}</p>
                    <a href="{{ url('administrator/data_tenagakerja') }}" class="inline-flex items-center mt-4 text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                        Cek Detail
                        <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Total Sumbangan -->
                <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-2xl shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-emerald-600 rounded-lg text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <h5 class="text-gray-600 font-medium">Total Sumbangan</h5>
                    <p class="text-3xl font-bold text-emerald-900 mt-1">0</p>
                    <a href="#" class="inline-flex items-center mt-4 text-sm font-semibold text-emerald-600 hover:text-emerald-800">
                        Cek Detail
                        <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Total Iuran -->
                <div class="bg-amber-50 border border-amber-100 p-6 rounded-2xl shadow-sm transition hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-amber-600 rounded-lg text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <h5 class="text-gray-600 font-medium">Total Iuran</h5>
                    <p class="text-3xl font-bold text-amber-900 mt-1">{{ format_rupiah($totalpunia) }}</p>
                    <a href="#" class="inline-flex items-center mt-4 text-sm font-semibold text-amber-600 hover:text-amber-800">
                        Cek Detail
                        <svg class="w-4 h-4 ms-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Grafik Sumbangan & Iuran ( {{ date("Y") }} )</h3>
                    <div class="flex space-x-2">
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-blue-600 rounded-full me-2"></span>
                            <span class="text-xs text-gray-600">Iuran</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 bg-yellow-400 rounded-full me-2"></span>
                            <span class="text-xs text-gray-600">Sumbangan</span>
                        </div>
                    </div>
                </div>
                <div class="relative w-full" style="height: 350px;">
                    <canvas id="dashboardChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async function () {
            try {
                const response = await fetch('{{ url('administrator/get_danapunia_range') }}');
                if (!response.ok) throw new Error('Network response was not ok');
                
                const data = await response.json();
                const parses = JSON.parse(data.total_punia);
                
                const total_sumbangan = parses.map(item => parseInt(item.punia));
                const total_iuran = [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4];

                const ctx = document.getElementById('dashboardChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        datasets: [
                            {
                                label: 'Dana Iuran',
                                data: total_sumbangan,
                                backgroundColor: '#044c92',
                                borderRadius: 6,
                                barThickness: 20
                            },
                            {
                                label: 'Dana Sumbangan',
                                data: total_iuran,
                                backgroundColor: '#fbbf24',
                                borderRadius: 6,
                                barThickness: 20
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0, 0, 0, 0.05)' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error('Error fetching chart data:', error);
            }
        });
    </script>
</x-app-layout>
