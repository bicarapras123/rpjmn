<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Monitoring') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            .aksi-col {
                display: none !important;
            }
        }
    </style>
    @endpush

    <div class="py-6" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-2">
                    <h3 class="text-lg font-semibold">Daftar Monitoring</h3>
                    <div class="flex flex-wrap gap-2 no-print">
                        @if(Auth::user()->role === 'viewer' || Auth::user()->role === 'admin')
                            <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                                Cetak Monitoring
                            </button>
                        @endif
                        @if(Auth::user()->role === 'admin')
                        <form action="{{ route('monitoring.hapusSemua') }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus SEMUA data?')" class="no-print">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-700 hover:bg-red-800 text-white px-4 py-2 rounded shadow">
                                Hapus Semua Data
                            </button>
                        </form>
                    @endif

                        
                        @if(Auth::user()->role === 'admin')
                            <button 
                                @click="open = true"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                                Tambah Monitoring
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="w-full border text-sm text-left text-gray-700">
                        <thead class="bg-blue-900 text-white text-center">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Indikator</th>
                                <th class="px-4 py-2 border">Direktorat</th>
                                <th class="px-4 py-2 border">KL Pelaksana</th>
                                <th class="px-4 py-2 border">Baseline</th>
                                <th class="px-4 py-2 border">2019</th>
                                <th class="px-4 py-2 border">2020</th>
                                <th class="px-4 py-2 border">2021</th>
                                <th class="px-4 py-2 border">2022</th>
                                <th class="px-4 py-2 border">Target</th>
                                @if (Auth::user()->role === 'admin')
                                    <th class="px-4 py-2 border aksi-col no-print">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
    @forelse($indikators as $item)
        <tr class="text-center">
            <td class="border px-2 py-1">{{ $loop->iteration }}</td>
            <td class="border px-2 py-1">{{ $item->indikator }}</td>
            <td class="border px-2 py-1">{{ $item->direktorat }}</td>
            <td class="border px-2 py-1">{{ $item->kl_pelaksana }}</td>
            <td class="border px-2 py-1">{{ $item->baseline }}</td>
            <td class="border px-2 py-1">{{ $item->tahun_2019 }}</td>
            <td class="border px-2 py-1">{{ $item->tahun_2020 }}</td>
            <td class="border px-2 py-1">{{ $item->tahun_2021 }}</td>
            <td class="border px-2 py-1">{{ $item->tahun_2022 }}</td>
            <td class="border px-2 py-1">{{ $item->target }}</td>

            @if(Auth::user()->role === 'admin')
                <td class="border px-2 py-1 aksi-col no-print">
                    {{-- Tombol Edit --}}
                    <a href="{{ route('monitoring.edit', $item->id) }}"
                       class="bg-yellow-400 hover:bg-yellow-500 text-white px-2 py-1 rounded mr-1">
                        Edit
                    </a>

                    {{-- Tombol Hapus --}}
                    <form action="{{ route('monitoring.destroy', $item->id) }}"
                          method="POST"
                          class="inline-block"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">
                            Hapus
                        </button>
                    </form>
                </td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="11" class="text-center py-4 text-gray-500">Tidak ada data indikator.</td>
        </tr>
    @endforelse
</tbody>


                {{-- Modal Tambah Monitoring --}}
                @if(Auth::user()->role === 'admin')
                    <div 
                        x-show="open"
                        x-transition
                        x-cloak
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 no-print"
                    >
                        <div @click.away="open = false" class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
                            <h2 class="text-lg font-semibold mb-4">Tambah Monitoring</h2>
                            <form action="{{ route('monitoring.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="text" name="indikator" placeholder="Indikator" class="w-full border border-gray-300 p-2 rounded" required>
                                <input type="text" name="direktorat" placeholder="Direktorat" class="w-full border border-gray-300 p-2 rounded" required>
                                <input type="text" name="kl_pelaksana" placeholder="KL Pelaksana" class="w-full border border-gray-300 p-2 rounded" required>
                                <input type="number" name="baseline" placeholder="Baseline" class="w-full border border-gray-300 p-2 rounded" required>
                                <input type="number" name="tahun_2019" placeholder="2019" class="w-full border border-gray-300 p-2 rounded" required>
                                <input type="number" name="tahun_2020" placeholder="2020" class="w-full border border-gray-300 p-2 rounded" required>
                                <input type="number" name="tahun_2021" placeholder="2021" class="w-full border border-gray-300 p-2 rounded" required>
                                <input type="number" name="tahun_2022" placeholder="2022" class="w-full border border-gray-300 p-2 rounded" required>
                                <input type="number" name="target" placeholder="Target" class="w-full border border-gray-300 p-2 rounded" required>

                                <div class="flex justify-between pt-4">
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                                        Simpan
                                    </button>
                                    <button type="button" @click="open = false" class="text-gray-600">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
