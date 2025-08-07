<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Monitoring') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded shadow-md">
                <form method="POST" action="{{ route('monitoring.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="indikator" value="Indikator" />
                            <x-text-input id="indikator" class="block mt-1 w-full" type="text" name="indikator" :value="old('indikator')" required autofocus />
                            <x-input-error :messages="$errors->get('indikator')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="direktorat" value="Direktorat" />
                            <x-text-input id="direktorat" class="block mt-1 w-full" type="text" name="direktorat" :value="old('direktorat')" required />
                            <x-input-error :messages="$errors->get('direktorat')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="kl_pelaksana" value="KL Pelaksana" />
                            <x-text-input id="kl_pelaksana" class="block mt-1 w-full" type="text" name="kl_pelaksana" :value="old('kl_pelaksana')" required />
                            <x-input-error :messages="$errors->get('kl_pelaksana')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="baseline" value="Baseline" />
                            <x-text-input id="baseline" class="block mt-1 w-full" type="number" step="any" name="baseline" :value="old('baseline')" required />
                            <x-input-error :messages="$errors->get('baseline')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tahun_2019" value="Tahun 2019" />
                            <x-text-input id="tahun_2019" class="block mt-1 w-full" type="number" step="any" name="tahun_2019" :value="old('tahun_2019')" required />
                            <x-input-error :messages="$errors->get('tahun_2019')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tahun_2020" value="Tahun 2020" />
                            <x-text-input id="tahun_2020" class="block mt-1 w-full" type="number" step="any" name="tahun_2020" :value="old('tahun_2020')" required />
                            <x-input-error :messages="$errors->get('tahun_2020')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tahun_2021" value="Tahun 2021" />
                            <x-text-input id="tahun_2021" class="block mt-1 w-full" type="number" step="any" name="tahun_2021" :value="old('tahun_2021')" required />
                            <x-input-error :messages="$errors->get('tahun_2021')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tahun_2022" value="Tahun 2022" />
                            <x-text-input id="tahun_2022" class="block mt-1 w-full" type="number" step="any" name="tahun_2022" :value="old('tahun_2022')" required />
                            <x-input-error :messages="$errors->get('tahun_2022')" class="mt-2" />
                        </div>

                        <div class="md:col-span-2">
                            <x-input-label for="target" value="Target" />
                            <x-text-input id="target" class="block mt-1 w-full" type="number" step="any" name="target" :value="old('target')" required />
                            <x-input-error :messages="$errors->get('target')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-primary-button class="ml-3">
                            {{ __('Simpan') }}
                        </x-primary-button>
                        <a href="{{ route('monitoring.index') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 focus:outline-none">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
