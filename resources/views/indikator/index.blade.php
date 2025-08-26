<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Monitoring Indikator</h2>
            
            {{-- Tombol tambah hanya untuk admin --}}
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('indikator.create') }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                    + Tambah Indikator
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-6">
                
                {{-- Notifikasi sukses --}}
                @if(session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Legend Warna --}}
                <div class="mb-4 p-3 bg-gray-100 rounded shadow text-sm">
                    <span class="inline-block w-4 h-4 bg-green-200 border mr-2"></span> 
                    <span class="mr-4">Approved (Tercapai / Disetujui)</span>

                    <span class="inline-block w-4 h-4 bg-red-200 border mr-2"></span> 
                    <span class="mr-4">Rejected (Tidak Tercapai / Ditolak)</span>

                    <span class="inline-block w-4 h-4 bg-gray-200 border mr-2"></span> 
                    <span>Belum divalidasi</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border border-blue-200 rounded-lg shadow-md">
                        <thead class="bg-blue-600 text-white text-sm uppercase tracking-wide">
                            <tr>
                                <th class="px-4 py-3 border border-blue-300">No</th>
                                <th class="px-4 py-3 border border-blue-300">Indikator</th>
                                <th class="px-4 py-3 border border-blue-300">Direktorat</th>
                                <th class="px-4 py-3 border border-blue-300">K/L Pelaksana</th>
                                <th class="px-4 py-3 border border-blue-300">Baseline</th>
                                <th class="px-4 py-3 border border-blue-300">2019</th>
                                <th class="px-4 py-3 border border-blue-300">2020</th>
                                <th class="px-4 py-3 border border-blue-300">2021</th>
                                <th class="px-4 py-3 border border-blue-300">2022</th>
                                <th class="px-4 py-3 border border-blue-300">Target</th>
                                <th class="px-4 py-3 border border-blue-300">Status</th>
                                <th class="px-4 py-3 border border-blue-300 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-700">
                            @forelse($indikators as $indikator)
                                <tr class="hover:bg-blue-50 transition duration-200">
                                    <td class="px-4 py-2 border border-blue-100 text-center font-medium">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-2 border border-blue-100">{{ $indikator->indikator }}</td>
                                    <td class="px-4 py-2 border border-blue-100">{{ $indikator->direktorat }}</td>
                                    <td class="px-4 py-2 border border-blue-100">{{ $indikator->kl_pelaksana }}</td>
                                    <td class="px-4 py-2 border border-blue-100 text-center">{{ $indikator->baseline }}</td>
                                    <td class="px-4 py-2 border border-blue-100 text-center">{{ $indikator->tahun_2019 }}</td>
                                    <td class="px-4 py-2 border border-blue-100 text-center">{{ $indikator->tahun_2020 }}</td>
                                    <td class="px-4 py-2 border border-blue-100 text-center">{{ $indikator->tahun_2021 }}</td>
                                    <td class="px-4 py-2 border border-blue-100 text-center">{{ $indikator->tahun_2022 }}</td>
                                    <td class="px-4 py-2 border border-blue-100 text-center font-semibold">{{ $indikator->target }}</td>
                                    <td class="px-4 py-2 border border-blue-100 text-center">
                                        <span class="px-2 py-1 rounded text-xs font-semibold
                                            @if($indikator->status === 'approved')
                                                bg-green-100 text-green-700 border border-green-300
                                            @elseif($indikator->status === 'rejected')
                                                bg-red-100 text-red-700 border border-red-300
                                            @else
                                                bg-gray-100 text-gray-700 border border-gray-300
                                            @endif">
                                            {{ ucfirst($indikator->status ?? 'Not yet') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border border-blue-100 text-center space-x-1">
                                        @if(Auth::user()->role === 'admin')
                                            <a href="{{ route('indikator.edit', $indikator->id) }}" 
                                               class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 text-xs shadow">
                                                Edit
                                            </a>
                                            <form action="{{ route('indikator.destroy', $indikator->id) }}" 
                                                  method="POST" class="inline-block"
                                                  onsubmit="return confirm('Yakin mau hapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700 text-xs shadow">
                                                    Hapus
                                                </button>
                                            </form>
                                        @elseif(Auth::user()->role === 'moderator')
                                            {{-- Moderator: Validasi --}}
                                            <form action="{{ route('indikator.setStatus', [$indikator->id, 'approved']) }}" 
                                                  method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 text-xs shadow">
                                                    Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('indikator.setStatus', [$indikator->id, 'rejected']) }}" 
                                                  method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 text-xs shadow">
                                                    Reject
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4 text-gray-500">
                                        Belum ada data indikator
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
