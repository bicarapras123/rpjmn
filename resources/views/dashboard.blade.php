<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <!-- Box Informasi Login -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ __("You're logged in!") }}</p>

                    <p class="mt-2">
                        Anda login sebagai:
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ Auth::user()->role }}
                        </span>
                    </p>

                    @if(Auth::user()->role === 'admin')
                        <div class="mt-4 text-green-700 font-semibold">
                            Selamat datang Admin! Anda memiliki akses penuh.
                        </div>
                    @elseif(Auth::user()->role === 'moderator')
                        <div class="mt-4 text-blue-700 font-semibold">
                            Selamat datang Moderator! Anda hanya bisa memvalidasi data monitoring.
                        </div>
                    @elseif(Auth::user()->role === 'viewer')
                        <div class="mt-4 text-gray-700 font-semibold">
                            Anda login sebagai Viewer. Beberapa fitur mungkin tidak tersedia.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Grafik Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Grafik Garis Per Direktorat -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold mb-4">Statistik Garis Per Direktorat</h3>
                    <canvas id="lineDirektorat"></canvas>
                </div>

                <!-- Grafik Garis Total Tiap Tahun -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold mb-4">Statistik Garis Total Tiap Tahun</h3>
                    <canvas id="lineTahun"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // === Statistik Garis Per Direktorat ===
        const ctxDirektorat = document.getElementById('lineDirektorat').getContext('2d');
        const lineDirektorat = new Chart(ctxDirektorat, {
            type: 'line',
            data: {
                labels: @json($direktoratLabels),
                datasets: [{
                    label: 'Jumlah Data per Direktorat',
                    data: @json($direktoratCounts),
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(59, 130, 246, 1)',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Jumlah Data per Direktorat',
                        font: { size: 16 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Direktorat'
                        }
                    }
                }
            }
        });

        // === Statistik Garis Total Tiap Tahun ===
        const ctxTahun = document.getElementById('lineTahun').getContext('2d');
        const lineTahun = new Chart(ctxTahun, {
            type: 'line',
            data: {
                labels: ['2019', '2020', '2021', '2022'],
                datasets: [{
                    label: 'Total Nilai per Tahun',
                    data: [
                        {{ $tahun['2019'] }},
                        {{ $tahun['2020'] }},
                        {{ $tahun['2021'] }},
                        {{ $tahun['2022'] }}
                    ],
                    borderColor: 'rgba(16, 185, 129, 1)',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Total Nilai per Tahun',
                        font: { size: 16 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Nilai Total' },
                        ticks: { precision: 0 }
                    },
                    x: {
                        title: { display: true, text: 'Tahun' }
                    }
                }
            }
        });
    </script>
</x-app-layout>
