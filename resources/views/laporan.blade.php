<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-4 px-2">

            {{-- Form Laporan --}}
            <div class="bg-white shadow-md rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Form Laporan</h3>

                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'viewer')
                <form action="{{ route('laporan.kirim') }}" method="POST" enctype="multipart/form-data" class="space-y-4">

                        @csrf

                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" name="nama" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                            <input type="text" name="unit" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Tujuan Email</label>
                            <input type="email" name="email" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="isi" class="block text-sm font-medium text-gray-700">Isi Laporan</label>
                            <textarea name="isi" rows="4" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500"></textarea>
                        </div>
                        <div>

                        <label for="file" class="block text-sm font-medium text-gray-700">Lampiran</label>
                        <input type="file" name="file" id="file" 
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        @error('file')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                        <div class="text-right">
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
                                Kirim Laporan
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-gray-600 text-sm">Anda hanya dapat melihat laporan. Akses input hanya untuk admin.</p>
                @endif
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 mt-6 p-4 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Form Pencarian --}}
            <form method="GET" action="{{ route('laporan.index') }}" class="mt-10 mb-4">
                <div class="flex flex-col sm:flex-row items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        placeholder="Cari nama, unit, atau email"
                        value="{{ request('search') }}"
                        class="w-full sm:w-1/2 px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        Cari
                    </button>
                </div>
            </form>

{{-- Tabel Semua Laporan --}}
@if($laporans->count())
    <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Daftar Laporan</h3>

        <div class="overflow-x-auto max-h-[500px] overflow-y-auto rounded-lg border border-gray-200">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 border text-center w-12">No</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Unit</th>
                        <th class="px-4 py-2 border">Email Tujuan</th>
                        <th class="px-4 py-2 border w-64">Isi</th>
                        <th class="px-4 py-2 border text-center w-24">Lampiran</th>
                        <th class="px-4 py-2 border text-center w-40">Waktu</th>
                        <th class="px-4 py-2 border text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($laporans as $laporan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 text-center">
                                {{ ($laporans->currentPage() - 1) * $laporans->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-2">{{ $laporan->nama }}</td>
                            <td class="px-4 py-2">{{ $laporan->unit }}</td>
                            <td class="px-4 py-2">{{ $laporan->email }}</td>
                            <td class="px-4 py-2">
                                <div class="max-w-xs truncate" title="{{ $laporan->isi }}">
                                    {{ $laporan->isi }}
                                </div>
                            </td>
                            <td class="px-4 py-2 text-center">
                                @if($laporan->file)
                                    <a href="{{ asset('storage/' . $laporan->file) }}"
                                       target="_blank"
                                       class="text-blue-600 hover:underline">Lihat</a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-center text-sm text-gray-500">
                                <span class="laporan-time" data-time="{{ $laporan->created_at->format('Y-m-d\TH:i:s') }}"></span>
                            </td>
                            <td class="px-4 py-2 text-center">
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'viewer')
                                    <form action="{{ route('laporan.destroy', $laporan->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $laporans->withQueryString()->links() }}
        </div>
    </div>
@else
    <p class="mt-10 text-gray-500 text-sm">Belum ada laporan yang dikirim.</p>
@endif

    {{-- JavaScript update waktu --}}
    <script>
        function updateTimes() {
            const elements = document.querySelectorAll('.laporan-time');
            elements.forEach(el => {
                const waktu = new Date(el.getAttribute('data-time'));
                const now = new Date();
                const diff = Math.floor((now - waktu) / 1000); // dalam detik

                let display = '';
                if (diff < 60) {
                    display = `${diff} detik yang lalu`;
                } else if (diff < 3600) {
                    display = `${Math.floor(diff / 60)} menit yang lalu`;
                } else if (diff < 86400) {
                    display = `${Math.floor(diff / 3600)} jam yang lalu`;
                } else {
                    display = waktu.toLocaleString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }

                el.textContent = display;
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateTimes();
            setInterval(updateTimes, 10000); // update setiap 10 detik
        });
    </script>
</x-app-layout>
