<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Indikator
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('indikator.update', $indikator->id) }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700">Indikator</label>
                        <input type="text" name="indikator" 
                               value="{{ old('indikator', $indikator->indikator) }}"
                               class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-200" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">Direktorat</label>
                        <input type="text" name="direktorat" 
                               value="{{ old('direktorat', $indikator->direktorat) }}"
                               class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-200" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700">K/L Pelaksana</label>
                        <input type="text" name="kl_pelaksana" 
                               value="{{ old('kl_pelaksana', $indikator->kl_pelaksana) }}"
                               class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-200" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700">Baseline</label>
                            <input type="number" name="baseline" 
                                   value="{{ old('baseline', $indikator->baseline) }}"
                                   class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-gray-700">Target</label>
                            <input type="number" name="target" 
                                   value="{{ old('target', $indikator->target) }}"
                                   class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700">Tahun 2019</label>
                            <input type="number" name="tahun_2019" 
                                   value="{{ old('tahun_2019', $indikator->tahun_2019) }}"
                                   class="w-full border rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700">Tahun 2020</label>
                            <input type="number" name="tahun_2020" 
                                   value="{{ old('tahun_2020', $indikator->tahun_2020) }}"
                                   class="w-full border rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700">Tahun 2021</label>
                            <input type="number" name="tahun_2021" 
                                   value="{{ old('tahun_2021', $indikator->tahun_2021) }}"
                                   class="w-full border rounded-md px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-gray-700">Tahun 2022</label>
                            <input type="number" name="tahun_2022" 
                                   value="{{ old('tahun_2022', $indikator->tahun_2022) }}"
                                   class="w-full border rounded-md px-3 py-2">
                        </div>
                    </div>

                    {{-- Upload File --}}
                    <div class="mb-4">
                        <label class="block text-gray-700">Upload File (Opsional)</label>
                        <input type="file" name="file" 
                               class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-blue-200">

                        @if ($indikator->file)
                            <p class="mt-2 text-sm text-gray-600">
                                File saat ini: 
                                <a href="{{ asset('storage/' . $indikator->file) }}" 
                                   class="text-blue-600 underline" target="_blank">
                                    Lihat File
                                </a>
                            </p>
                        @endif
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('indikator.index') }}" 
                           class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            Kembali
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
