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
                    @elseif(Auth::user()->role === 'viewer')
                        <div class="mt-4 text-gray-700 font-semibold">
                            Anda login sebagai Viewer. Beberapa fitur mungkin tidak tersedia.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Grafik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Grafik Pie -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold mb-4">Grafik Pie Per Direktorat</h3>
                    <canvas id="pieChart"></canvas>
                </div>

                <!-- Grafik Batang -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold mb-4">Grafik Batang Total Tiap Tahun</h3>
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: @json($direktoratLabels),
                datasets: [{
                    label: 'Jumlah Data per Direktorat',
                    data: @json($direktoratCounts),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                        'rgba(199, 199, 199, 0.7)'
                    ],
                    borderColor: 'rgba(255,255,255,1)',
                    borderWidth: 1
                }]
            }
        });

        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
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
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
