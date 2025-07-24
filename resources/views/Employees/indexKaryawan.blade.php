@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Karyawan</h3>
    <a href="/karyawan" class="inline-block text-blue-600 border-b border-gray-300">karyawan / </a>



    <div class="relative shadow-md p-2 sm:rounded-lg mt-5">
        {{-- dropdown --}}

        <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
            {{-- Reset Gaji & tambah --}}
            <div class="md:mt-2 flex justify-end w-full bg-amber-200 gap-2">
                <a href="#" data-modal-target="popup-modal-resetGajiKaryawan"
                    data-modal-toggle="popup-modal-resetGajiKaryawan"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300">
                    Reset Gaji Akhir
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                        class="w-4 h-4 ms-2 text-whitetransition duration-75  group-hover:text-gray-900 "
                        fill="currentColor">>
                        <path
                            d="M105.1 202.6c7.7-21.8 20.2-42.3 37.8-59.8c62.5-62.5 163.8-62.5 226.3 0L386.3 160 352 160c-17.7 0-32 14.3-32 32s14.3 32 32 32l111.5 0c0 0 0 0 0 0l.4 0c17.7 0 32-14.3 32-32l0-112c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 35.2L414.4 97.6c-87.5-87.5-229.3-87.5-316.8 0C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5zM39 289.3c-5 1.5-9.8 4.2-13.7 8.2c-4 4-6.7 8.8-8.1 14c-.3 1.2-.6 2.5-.8 3.8c-.3 1.7-.4 3.4-.4 5.1L16 432c0 17.7 14.3 32 32 32s32-14.3 32-32l0-35.1 17.6 17.5c0 0 0 0 0 0c87.5 87.4 229.3 87.4 316.7 0c24.4-24.4 42.1-53.1 52.9-83.8c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.5 62.5-163.8 62.5-226.3 0l-.1-.1L125.6 352l34.4 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L48.4 288c-1.6 0-3.2 .1-4.8 .3s-3.1 .5-4.6 1z" />
                    </svg>
                </a>
                <a href="{{ route('karyawan.create') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                    Tambah Karyawan
                    <i class="ms-2 fa-solid fa-plus"></i>
                </a>
            </div>
        </div>

        <table id="search-table" class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <span class="flex items-center">
                            Username
                            <svg class="w-4 h-4 ms-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="flex items-center">
                            Nama Lengkap
                            <svg class="w-4 h-4 ms-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3">Role</th>
                    <th scope="col" class="px-6 py-3">
                        <span class="flex items-center">
                            Devisi
                            <svg class="w-4 h-4 ms-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="flex items-center">
                            Gaji Pokok
                            <svg class="w-4 h-4 ms-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataKaryawan as $item)
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $item->username }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->nama_lengkap }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->role }}
                        </td>
                        @php
                            $devisi = $item->devisi->nama_devisi ?? '-';
                            $bgColor = match ($devisi) {
                                'operasional' => 'bg-yellow-100 text-yellow-800',
                                'keuangan' => 'bg-red-100 text-red-800',
                                'pemasaran' => 'bg-green-100 text-green-800',
                                'produksi 1' => 'bg-blue-900 text-white',
                                'produksi 2' => 'bg-blue-100 text-blue-800',
                                default => 'bg-gray-100 text-gray-700',
                            };
                        @endphp
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $bgColor }}">
                                {{ $devisi }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($item->gaji_pokok)
                                Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center space-x-3">
                            <a href="{{ route('karyawan.show', $item->id) }}"
                            class="font-medium text-blue-600 hover:underline">Detail</a>
                            <a href="{{ route('karyawan.edit', $item->id) }}"
                                class="font-medium text-yellow-600 hover:underline">Edit</a>
                            <a href="#" onclick="openDeleteModal({{ $item->id }}, '{{ $item->username }}')"
                                data-modal-target="popup-modal-delete" data-modal-toggle="popup-modal-delete"
                                class="font-medium text-red-600 hover:underline">
                                Hapus
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>



        {{-- <div class="px-6 pt-4 pb-2">
            {{ $dataKaryawan->appends(request()->query())->links() }}
        </div> --}}

    </div>


    {{-- modal delete data karyawan --}}
    <div id="popup-modal-delete" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="popup-modal-delete">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <form id="deleteForm" class="p-4 md:p-5 text-center" method="POST">
                    @csrf
                    @method('DELETE')

                    <svg class="mx-auto mb-4 text-yellow-400 w-12 h-12 " aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500">
                        Apakah kamu yakin ingin menghapus akun karyawan ini?
                        <span id="modal-username" class="font-semibold text-gray-800"></span>
                    </h3>
                    <button type="submit"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Ya, hapus
                    </button>
                    <button type="button" data-modal-hide="popup-modal-delete"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                        Batal
                    </button>
                </form>
            </div>
        </div>
    </div>



    {{-- modal reset gaji akhir karyawan --}}
    <div id="popup-modal-resetGajiKaryawan" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow-sm ">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                    data-modal-hide="popup-modal-resetGajiKaryawan">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <form class="p-4 md:p-5 text-center" action="{{ route('gaji.reset_all') }}" method="POST">
                    @csrf
                    <svg class="mx-auto mb-4 text-yellow-400 w-12 h-12 " aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 border-b border-gray-400">Apakah kamu yakin ingin
                        melakukan reset seluruh
                        Gaji akhir karyawan?</h3>
                    {{-- Teks kecil yang ditambahkan di sini --}}
                    <p class="text-sm text-red-400 mb-5">
                        Reset gaji dilakukan tiap tanggal 25. Pastikan rekap absensi bulan ini sudah diekspor sebelum
                        mereset.
                    </p>

                    <button data-modal-hide="popup-modal-resetGajiKaryawan" type="submit"
                        class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                        Ya, Reset
                    </button>
                    <button data-modal-hide="popup-modal-resetGajiKaryawan" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Batal</button>
                </form>
            </div>
        </div>
    </div>

    {{-- js search table and sortir from flowbite datatables --}}
    <script>
        if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#search-table", {
                searchable: true,
                sortable: true, // enable or disable sorting
                locale: "en-US", // set the locale for sorting
                numeric: true, // enable or disable numeric sorting
                string: true,
                caseFirst: "false", // set the case first for sorting (upper, lower)
                ignorePunctuation: true, // enable or disable punctuation sorting
            });
        }
    </script>

    {{-- script delete data --}}
    <script>
        function openDeleteModal(id) {
            const form = document.getElementById('deleteForm');
            form.action = `/karyawan/${id}`; // Ubah sesuai dengan route destroy kamu

            const modal = document.getElementById('delete-modal');
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('delete-modal');
            modal.classList.add('hidden');
        }
    </script>


    {{-- js seach dan filter
    <script>
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
