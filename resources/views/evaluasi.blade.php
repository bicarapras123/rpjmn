<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Evaluasi Monitoring Indikator</h2>

            <!-- Tombol Cetak -->
            <button onclick="printPage()" 
                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium shadow print:hidden">
                Cetak Evaluasi
            </button>
        </div>
    </x-slot>

    <div class="py-8" id="print-area">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            <!-- Ringkasan Tahunan -->
            <div class="bg-white shadow-xl rounded-xl p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Ringkasan Tahunan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($tahun as $thn => $jumlah)
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white p-4 rounded-xl shadow-md">
                            <div class="text-sm uppercase tracking-wider">Tahun {{ $thn }}</div>
                            <div class="text-3xl font-bold mt-2">{{ number_format($jumlah) }}</div>
                        </div> 
                    @endforeach
                </div>
            </div>

            <!-- Analisis Data -->
            <div class="bg-white shadow-xl rounded-xl p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Analisis Data</h3>
                <ul class="list-disc list-inside text-sm text-gray-700 space-y-2">
                    <li><strong>Puncak:</strong> Tahun 
                        <span class="font-bold text-blue-600">{{ $tahun->sortDesc()->keys()->first() }}</span> 
                        dengan <span class="font-bold">{{ $tahun->max() }}</span> indikator
                    </li>
                    <li><strong>Terendah:</strong> Tahun 
                        <span class="font-bold text-red-600">{{ $tahun->sort()->keys()->first() }}</span> 
                        hanya <span class="font-bold">{{ $tahun->min() }}</span> indikator
                    </li>
                    <li><strong>Total Direktorat:</strong> {{ count($direktoratLabels) }} unit kerja</li>
                    <li><strong>Direktorat:</strong>
                        @foreach($direktoratLabels as $label)
                            <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs mr-1 mt-1">{{ $label }}</span>
                        @endforeach
                    </li>
                </ul>
            </div>
<!-- Hasil Evaluasi Keseluruhan -->
<div class="bg-white shadow-xl rounded-xl p-6">
    <h3 class="text-xl font-semibold mb-4 text-gray-800">Hasil Evaluasi Keseluruhan</h3>
    
    @php
        $totalTahun = $tahun->count();
        $totalIndikator = $tahun->sum(); // jumlah semua indikator dari semua tahun
        $tahunTertinggi = $tahun->sortDesc()->keys()->first();
        $jumlahTertinggi = $tahun->max();
        $tahunTerendah = $tahun->sort()->keys()->first();
        $jumlahTerendah = $tahun->min();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 text-center">
        <div class="bg-blue-100 p-4 rounded-xl shadow">
            <div class="text-2xl font-bold text-blue-700">{{ $totalTahun }}</div>
            <div class="text-sm text-blue-600">Jumlah Tahun Data</div>
        </div>
        <div class="bg-green-100 p-4 rounded-xl shadow">
            <div class="text-2xl font-bold text-green-700">{{ $totalIndikator }}</div>
            <div class="text-sm text-green-600">Total Indikator</div>
        </div>
        <div class="bg-yellow-100 p-4 rounded-xl shadow">
            <div class="text-lg font-bold text-yellow-700">Tahun {{ $tahunTertinggi }}</div>
            <div class="text-sm text-yellow-600">{{ $jumlahTertinggi }} indikator (Tertinggi)</div>
        </div>
        <div class="bg-red-100 p-4 rounded-xl shadow">
            <div class="text-lg font-bold text-red-700">Tahun {{ $tahunTerendah }}</div>
            <div class="text-sm text-red-600">{{ $jumlahTerendah }} indikator (Terendah)</div>
        </div>
    </div>

    <p class="mt-4 text-sm text-gray-700">
        Berdasarkan hasil evaluasi monitoring tahunan, terdapat data selama 
        <span class="font-bold text-blue-600">{{ $totalTahun }}</span> tahun dengan total 
        <span class="font-bold">{{ $totalIndikator }}</span> indikator. 
        Jumlah indikator terbanyak tercatat pada tahun 
        <span class="font-bold text-yellow-600">{{ $tahunTertinggi }}</span> 
        dengan <span class="font-bold">{{ $jumlahTertinggi }}</span> indikator, 
        sedangkan jumlah terendah pada tahun 
        <span class="font-bold text-red-600">{{ $tahunTerendah }}</span> 
        dengan <span class="font-bold">{{ $jumlahTerendah }}</span> indikator.
    </p>
</div>

            <!-- Diagram Visual -->
            <div class="bg-white shadow-xl rounded-xl p-6">
                <h3 class="text-xl font-semibold mb-6 text-gray-800">Visualisasi Data</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pie Chart -->
                    <div>
                        <h4 class="text-center font-medium text-gray-700 mb-2">Distribusi per Direktorat</h4>
                        <div class="h-80">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>

                    <!-- Bar Chart -->
                    <div>
                        <h4 class="text-center font-medium text-gray-700 mb-2">Jumlah Indikator per Tahun</h4>
                        <div class="h-80">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Rincian Indikator -->
            <div class="bg-white shadow-xl rounded-xl p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Rincian Data Indikator</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border">No</th>
                                <th class="py-2 px-4 border">Indikator</th>
                                <th class="py-2 px-4 border">Direktorat</th>
                                <th class="py-2 px-4 border">Pelaksana</th>
                                <th class="py-2 px-4 border text-center">Baseline</th>
                                <th class="py-2 px-4 border text-center">2019</th>
                                <th class="py-2 px-4 border text-center">2020</th>
                                <th class="py-2 px-4 border text-center">2021</th>
                                <th class="py-2 px-4 border text-center">2022</th>
                                <th class="py-2 px-4 border text-center">Target</th>
                                <th class="py-2 px-4 border text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($indikators as $i => $indikator)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-4 border">{{ $i + 1 }}</td>
                                    <td class="py-2 px-4 border">{{ $indikator->indikator }}</td>
                                    <td class="py-2 px-4 border">{{ $indikator->direktorat }}</td>
                                    <td class="py-2 px-4 border">{{ $indikator->kl_pelaksana }}</td>
                                    <td class="py-2 px-4 border text-center">{{ $indikator->baseline }}</td>
                                    <td class="py-2 px-4 border text-center">{{ $indikator->tahun_2019 }}</td>
                                    <td class="py-2 px-4 border text-center">{{ $indikator->tahun_2020 }}</td>
                                    <td class="py-2 px-4 border text-center">{{ $indikator->tahun_2021 }}</td>
                                    <td class="py-2 px-4 border text-center">{{ $indikator->tahun_2022 }}</td>
                                    <td class="py-2 px-4 border text-center">{{ $indikator->target }}</td>
                                    <td class="py-2 px-4 border text-center">
                                        <span class="px-2 py-1 text-xs rounded 
                                            {{ $indikator->status == 'aktif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            {{ ucfirst($indikator->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-3 text-red-500">Tidak ada data indikator.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
        new Chart(document.getElementById('pieChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: direktoratLabels,
                datasets: [{
                    label: 'Jumlah Indikator',
                    data: direktoratCounts,
                    backgroundColor: [
                        '#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF','#FF9F40','#8BC34A'
                    ]
                }]
            }
        });

        // BAR
        new Chart(document.getElementById('barChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: tahunLabels,
                datasets: [{
                    label: 'Indikator per Tahun',
                    data: tahunCounts,
                    backgroundColor: '#36A2EB',
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

    <!-- Print Style -->
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
