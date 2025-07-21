@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Karyawan</h3>
    <a href="/karyawan" class="inline-block text-blue-600 border-b border-gray-300">karyawan / </a>



    <div class="relative shadow-md p-2 sm:rounded-lg mt-5">
        {{-- dropdown --}}


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
                            <svg class="w-4 h-4 ms-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <span class="flex items-center">
                            Gaji Pokok
                            <svg class="w-4 h-4 ms-1" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
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
                            <a href="#" class="font-medium text-red-600 hover:underline"
                                data-modal-target="popup-modal-{{ $item->id }}"
                                data-modal-toggle="popup-modal-{{ $item->id }}">Hapus</a>
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
    @foreach ($dataKaryawan as $item)
        <div id="popup-modal-{{ $item->id }}" tabindex="-1"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm ">
                    <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                        data-modal-hide="popup-modal-{{ $item->id }}">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <form class="p-4 md:p-5 text-center" action="{{ route('karyawan.destroy', $item->id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')

                        <svg class="mx-auto mb-4 text-yellow-400 w-12 h-12 " aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 ">Apakah kamu yakin ingin menghapus akun karyawan
                            ini?</h3>
                        <button data-modal-hide="popup-modal-{{ $item->id }}" type="submit"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300  font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Ya, hapus
                        </button>
                        <button data-modal-hide="popup-modal-{{ $item->id }}" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 ">Batal</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach


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
