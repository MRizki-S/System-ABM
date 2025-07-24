@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Jam Kerja Devisi</h3>
    <a href="/jam-kerja" class="inline-block text-blue-600 border-b border-gray-300">Jam Kerja Devisi / </a>

    {{-- alert error validasi --}}
    @if ($errors->any())
        <div class="flex p-4 mt-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 " role="alert">
            <svg class="shrink-0 inline w-4 h-4 me-3 mt-[2px]" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <div>
                <span class="font-medium">Terjadi kesalahan:</span>
                <ul class="mt-1.5 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    <div class="relative shadow-md sm:rounded-lg mt-5">

        <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-end pb-4">

            @if (Auth::user()->role == 'superadmin')
                {{-- tambah --}}
                <div class="md:mt-2 ">
                    <a href="#" data-modal-target="modal-create" data-modal-toggle="modal-create"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        Tambah Data
                        <i class="ms-2 fa-solid fa-plus"></i>
                    </a>
                </div>
            @endif
        </div>

        {{-- table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Devisi</th>
                        <th scope="col" class="px-6 py-3">Nama Jam Kerja</th>
                        <th scope="col" class="px-6 py-3">Jam Mulai</th>
                        <th scope="col" class="px-6 py-3">Jam Selesai</th>
                        @if (Auth::user()->role == 'superadmin')
                            <th scope="col" class="px-6 py-3">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataJamKerja as $item)
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            @php
                                $devisi = $item->nama_devisi ?? '-';
                                $bgColor = match ($devisi) {
                                    'operasional' => 'bg-yellow-100 text-yellow-800',
                                    'keuangan' => 'bg-red-100 text-red-800',
                                    'pemasaran' => 'bg-green-100 text-green-800',
                                    'produksi 1' => 'bg-blue-900 text-white',
                                    'produksi 2' => 'bg-blue-100 text-blue-800',
                                    'operasional - keuangan - pemasaran' => 'bg-orange-200 text-orange-800',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $bgColor }}">
                                    {{ $devisi }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->nama_jamkerja }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->jam_mulai }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->jam_selesai }}
                            </td>
                            @if (Auth::user()->role == 'superadmin')
                                <td class="px-6 py-4 space-x-3">
                                    <a href="#" class="font-medium text-yellow-600 hover:underline"
                                        data-modal-target="modal-edit-{{ $item->id }}"
                                        data-modal-toggle="modal-edit-{{ $item->id }}">Edit</a>
                                    <a href="#" class="font-medium text-red-600 hover:underline"
                                        data-modal-target="popup-modal-{{ $item->id }}"
                                        data-modal-toggle="popup-modal-{{ $item->id }}">Hapus</a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        {{-- <div class="px-6 pt-4 pb-2">
            {{ $dataJamKerja->appends(request()->query())->links() }}
        </div> --}}

    </div>

    <!-- Modal Create Jam kerja -->
    <div id="modal-create" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm ">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t  border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 ">
                        Tambah Jam Kerja Devisi
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                        data-modal-toggle="modal-create">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" action="{{ route('jam-kerja.store') }}" method="POST">
                    {{-- CSRF token for security --}}
                    @csrf

                    <div class="grid gap-4 mb-4 grid-cols-2">
                        {{-- nama devisi --}}
                        <div class="col-span-2">
                            <label for="nama_devisi" class="block mb-2 text-sm font-medium text-gray-900 ">Nama
                                Devisi</label>
                            <input type="text" name="nama_devisi" id="nama_devisi"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                                placeholder="Nama devisi..." required="">
                        </div>
                        {{-- nama jam kerja --}}
                        <div class="col-span-2">
                            <label for="nama_jamkerja" class="block mb-2 text-sm font-medium text-gray-900 ">Nama Jam
                                Kerja</label>
                            <input type="text" name="nama_jamkerja" id="nama_jamkerja"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                                placeholder="Nama jam kerja..." required="">
                        </div>

                        {{-- jam mulai & jam selesai --}}
                        <div>
                            <label for="start-time" class="block mb-2 text-sm font-medium text-gray-900">Start time:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="time" id="start-time" name="jam_mulai"
                                    class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                    min="" max="24:00" value="00:00" required />
                            </div>
                        </div>
                        <div>
                            <label for="end-time" class="block mb-2 text-sm font-medium text-gray-900">End
                                time:</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input type="time" id="end-time" name="jam_selesai"
                                    class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                    min="" max="18:00" value="00:00" required />
                            </div>
                        </div>
                    </div>

                    {{-- submit button --}}
                    <div class="w-full flex justify-end">
                        <button type="submit"
                            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">
                            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Jam kerja -->
    @foreach ($dataJamKerja as $item)
        <div id="modal-edit-{{ $item->id }}" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm ">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t  border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 ">
                            Edit Jam Kerja Devisi
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                            data-modal-toggle="modal-edit-{{ $item->id }}">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <form class="p-4 md:p-5" action="{{ route('jam-kerja.update', $item->id) }}" method="POST">
                        {{-- CSRF token for security --}}
                        @csrf
                        @method('PUT')

                        <div class="grid gap-4 mb-4 grid-cols-2">
                            {{-- nama devisi --}}
                            <div class="col-span-2">
                                <label for="nama_devisi" class="block mb-2 text-sm font-medium text-gray-900 ">Nama
                                    Devisi</label>
                                <input type="text" name="nama_devisi" id="nama_devisi"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                                    placeholder="Nama devisi..." required value="{{ $item->nama_devisi }}">
                            </div>
                            {{-- nama jam kerja --}}
                            <div class="col-span-2">
                                <label for="nama_jamkerja" class="block mb-2 text-sm font-medium text-gray-900 ">Nama Jam
                                    Kerja</label>
                                <input type="text" name="nama_jamkerja" id="nama_jamkerja"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 "
                                    placeholder="Nama jam kerja..." required value="{{ $item->nama_jamkerja }}">
                            </div>

                            {{-- jam mulai & jam selesai --}}
                            <div>
                                <label for="start-time" class="block mb-2 text-sm font-medium text-gray-900">Start
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="start-time" name="jam_mulai"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        min="" max="24:00" required
                                        value="{{ old('jam_mulai', $item->jam_mulai ? \Carbon\Carbon::createFromFormat('H:i:s', $item->jam_mulai)->format('H:i') : '') }}" />
                                </div>
                            </div>
                            <div>
                                <label for="end-time" class="block mb-2 text-sm font-medium text-gray-900">End
                                    time:</label>
                                <div class="relative">
                                    <div
                                        class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input type="time" id="end-time" name="jam_selesai"
                                        class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                                        min="" max="18:00" required
                                        value="{{ old('jam_selesai', $item->jam_selesai ? \Carbon\Carbon::createFromFormat('H:i:s', $item->jam_selesai)->format('H:i') : '') }}" />
                                </div>
                            </div>
                        </div>

                        {{-- submit button --}}
                        <div class="w-full flex justify-end">
                            <button type="submit"
                                class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">
                                <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    {{-- modal delete data JamKerja --}}
    @foreach ($dataJamKerja as $itemDelete)
        <div id="popup-modal-{{ $itemDelete->id }}" tabindex="-1"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm ">
                    <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                        data-modal-hide="popup-modal-{{ $itemDelete->id }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <form class="p-4 md:p-5 text-center" action="{{ route('jam-kerja.destroy', $itemDelete->id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')

                        <svg class="mx-auto mb-4 text-yellow-400 w-12 h-12 " aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 ">Apakah kamu yakin ingin menghapus data jam
                            kerja
                            ini?</h3>
                        <button data-modal-hide="popup-modal-{{ $itemDelete->id }}" type="submit"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Ya, hapus
                        </button>
                        <button data-modal-hide="popup-modal-{{ $itemDelete->id }}" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


    {{-- js seach dan filter --}}
    {{-- <script>
        const searchInput = document.getElementById('table-search');
        const dropdownLabel = document.getElementById('dropdownLabel');
        const dropdownBtn = document.getElementById('dropdownDevisiBtn');
        const dropdownList = document.getElementById('dropdownDevisiList');

        let currentFilter = 'all';

        // Toggle dropdown
        dropdownBtn.addEventListener('click', () => {
            dropdownList.classList.toggle('hidden');
        });

        // Saat item filter diklik
        document.querySelectorAll('#dropdownDevisiList a').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                currentFilter = this.getAttribute('data-filter');
                dropdownLabel.textContent = this.textContent;
                dropdownList.classList.add('hidden');
                filterAndSearch(); // jalankan filter dan pencarian ulang
            });
        });

        // Saat input diketik
        searchInput.addEventListener('input', filterAndSearch);

        function filterAndSearch() {
            const searchQuery = searchInput.value.toLowerCase();

            document.querySelectorAll('tbody tr').forEach(row => {
                const rowText = row.innerText.toLowerCase();
                const devisiCell = row.querySelector('td:nth-child(4)');
                const devisiText = devisiCell ? devisiCell.textContent.trim().toLowerCase() : '';

                const matchSearch = rowText.includes(searchQuery);
                const matchFilter = currentFilter === 'all' || devisiText === currentFilter;

                row.style.display = matchSearch && matchFilter ? '' : 'none';
            });
        }
    </script> --}}
@endsection
