<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Evaluasi Monitoring</h2>

            <!-- Tombol Cetak -->
            <button onclick="printPage()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 text-sm print:hidden">
                Cetak Halaman Evaluasi
            </button>
        </div>
    </x-slot>

    <div class="py-8" id="print-area">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Ringkasan dan Evaluasi Detail -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Ringkasan Tahunan -->
                <div class="bg-white shadow-lg rounded-lg p-6 lg:col-span-2">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Ringkasan Tahunan</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        @forelse ($tahun as $thn => $jumlah)
                            <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white p-4 rounded-xl shadow-md">
                                <div class="text-sm uppercase tracking-wider">Tahun {{ $thn }}</div>
                                <div class="text-3xl font-bold mt-2">{{ number_format($jumlah) }}</div>
                            </div>
                        @empty
                            <p class="col-span-full text-red-500">Tidak ada data tahun tersedia.</p>
                        @endforelse
                    </div>
                    <p class="text-sm text-gray-600 mt-4">Total jumlah indikator yang tercatat per tahun.</p>
                </div>

                <!-- Evaluasi Detail -->
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Evaluasi Detail</h3>
                    <ul class="list-disc list-inside text-sm text-gray-700 space-y-2">
                        <li><strong>Puncak:</strong> Tahun dengan jumlah indikator tertinggi: 
                            <span class="font-bold text-blue-600">{{ $tahun->sortDesc()->keys()->first() }}</span> 
                            ({{ $tahun->max() }} indikator)
                        </li>
                        <li><strong>Terendah:</strong> Tahun dengan indikator paling sedikit: 
                            <span class="font-bold text-red-600">{{ $tahun->sort()->keys()->first() }}</span> 
                            ({{ $tahun->min() }} indikator)
                        </li>
                        <li><strong>Total Direktorat:</strong> {{ count($direktoratLabels) }} unit kerja</li>
                        <li><strong>Direktorat Aktif:</strong>
                            @foreach($direktoratLabels as $label)
                                <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs mr-1 mt-1">{{ $label }}</span>
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Diagram Visual -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-6 text-gray-800">Visualisasi Indikator Berdasarkan Direktorat</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pie Chart -->
                    <div>
                        <h4 class="text-center font-medium text-gray-700 mb-2">Persentase (Pie Chart)</h4>
                        <div class="h-80">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>

                    <!-- Bar Chart -->
                    <div>
                        <h4 class="text-center font-medium text-gray-700 mb-2">Jumlah (Bar Chart)</h4>
                        <div class="h-80">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="text-sm text-gray-600 mt-4 space-y-1">
                    <p><strong>Pie Chart</strong> memperlihatkan proporsi indikator per direktorat secara visual.</p>
                    <p><strong>Bar Chart</strong> menyajikan jumlah indikator dengan nilai absolut per direktorat.</p>
                </div>
            </div>

            <!-- Tabel Rincian -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Rincian Data Direktorat</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border">#</th>
                                <th class="py-2 px-4 border">Nama Direktorat</th>
                                <th class="py-2 px-4 border text-center">Jumlah Indikator</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($direktoratLabels as $i => $nama)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border">{{ $i + 1 }}</td>
                                    <td class="py-2 px-4 border">{{ $nama }}</td>
                                    <td class="py-2 px-4 border text-center">{{ $direktoratCounts[$i] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-3 text-red-500">Tidak ada data direktorat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="text-sm text-gray-600 mt-4">Tabel ini menyajikan data lengkap indikator berdasarkan unit kerja direktorat.</p>
            </div>
        </div>
    </div>

    <!-- CDN Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart Script -->
    <script>
        const direktoratLabels = {!! json_encode($direktoratLabels ?? []) !!};
        const direktoratCounts = {!! json_encode($direktoratCounts ?? []) !!};
        const tahunLabels = {!! json_encode($tahun->keys() ?? []) !!};
        const tahunCounts = {!! json_encode($tahun->values() ?? []) !!};

        // PIE
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: direktoratLabels,
                datasets: [{
                    label: 'Jumlah',
                    data: direktoratCounts,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(201, 203, 207, 0.6)'
                    ]
                }]
            }
        });

        // BAR
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: tahunLabels,
                datasets: [{
                    label: 'Indikator per Tahun',
                    data: tahunCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                }]
            }
        });
    </script>

    <!-- Print Script -->
    <script>
        function printPage() {
            window.print();
        }
    </script>

    <!-- Tambahkan Tailwind Print Utility -->
    <style>
        @media print {
            .print\:hidden {
                display: none !important;
            }
            body {
                background-color: white;
            }
        }
    </style>
</x-app-layout>
