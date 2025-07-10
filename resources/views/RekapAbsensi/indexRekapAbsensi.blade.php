@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Rekap Absensi</h3>
    <a href="/rekap-absensi" class="inline-block text-blue-600 border-b border-gray-300 mb-5">Rekap Absensi / </a>

    @if ($errors->any())
        <div class="text-red-500 text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- Filter & Export --}}
    <div class="w-full flex flex-col md:flex-row md:items-end md:justify-end justify-end gap-4 mb-6">
        {{-- Tombol Export --}}
        <div class="w-full md:w-auto flex justify-start md:justify-end">
            <a href="#" data-modal-target="pop-up-modal-export-excel" data-modal-toggle="pop-up-modal-export-excel"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 shadow w-full md:w-auto justify-center">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
        </div>
    </div>

    {{-- Form Filter Tanggal --}}
    <div class="bg-white rounded-xl w-full md:w-auto mb-4">
        <form action="{{ route('rekap-absensi.index') }}" method="GET"
            class="flex flex-col md:flex-row md:items-end gap-3 md:gap-4">

            {{-- Input Tanggal --}}
            <div class="w-full md:w-64">
                <label for="tanggal_filter" class="block text-gray-700 text-sm font-semibold mb-1">
                    Pilih Tanggal Absensi:
                </label>
                <input type="date" id="tanggal_filter" name="tanggal_filter" value="{{ $selectedDate ?? '' }}"
                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            {{-- Tombol --}}
            <div class="flex flex-col md:flex-row gap-2 md:items-center mt-2 md:mt-0">
                {{-- Button Terapkan --}}
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                        class="w-4 h-4 me-2 text-white-500 transition duration-75  group-hover:text-gray-900 "
                        fill="currentColor">
                        <path
                            d="M3.9 54.9C10.5 40.9 24.5 32 40 32l432 0c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9 320 448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6l0-79.1L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z" />
                    </svg> Terapkan
                </button>

                {{-- Button Reset --}}
                <a href="{{ route('rekap-absensi.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-800 bg-gray-200 hover:bg-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                        class="w-4 h-4 me-2 text-white-500 transition duration-75  group-hover:text-gray-900 "
                        fill="currentColor">
                        <path
                            d="M48.5 224L40 224c-13.3 0-24-10.7-24-24L16 72c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2L98.6 96.6c87.6-86.5 228.7-86.2 315.8 1c87.5 87.5 87.5 229.3 0 316.8s-229.3 87.5-316.8 0c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0c62.5 62.5 163.8 62.5 226.3 0s62.5-163.8 0-226.3c-62.2-62.2-162.7-62.5-225.3-1L185 183c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8L48.5 224z" />
                    </svg>
                    Reset Filter
                </a>
            </div>
        </form>
    </div>





    <table id="search-table">
        <thead>
            <tr>
                <th scope="col" class="px-6 py-3">
                    <span class="flex items-center">
                        Nama Lengkap
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                    </span>
                </th>
                <th scope="col" class="px-6 py-3">Devisi</th>
                <th scope="col" class="px-6 py-3" data-type="date" data-format="DD-MM-YYYY"><span
                        class="flex items-center">
                        Tanggal
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                    </span>
                </th>
                <th scope="col" class="px-6 py-3"><span class="flex items-center">
                        Jam Masuk
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                    </span>
                </th>
                <th scope="col" class="px-6 py-3">
                    <span class="flex items-center">
                        Jam Keluar
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                    </span>
                </th>
                <th scope="col" class="px-6 py-3">
                    <span class="flex items-center">
                        Absen
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                    </span>
                </th>
                <th scope="col" class="px-6 py-3 text-center">
                    <span class="flex items-center">
                        Potongan
                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                        </svg>
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataRekapAbsensi as $item)
                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                    <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                        {{ $item->user->nama_lengkap }}
                    </td>
                    @php
                        $devisi = $item->user->devisi->nama_devisi ?? '-';
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
                        {{ $item->tanggal->format('d-m-Y') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->waktu_masuk ? $item->waktu_masuk : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $item->waktu_keluar ? $item->waktu_keluar : '-' }}
                    </td>
                    <td class="px-6 py-4">
                        @if ($item->jenis == 'check_in')
                            <span class="text-green-600 font-semibold">Check In</span>
                        @elseif ($item->jenis == 'check_out')
                            <span class="text-blue-600 font-semibold">Check Out</span>
                        @elseif ($item->jenis == 'sakit')
                            <span class="text-yellow-600 font-semibold">Sakit</span>
                        @elseif ($item->jenis == 'izin')
                            <span class="text-red-600 font-semibold">Izin</span>
                        @else
                            <span class="text-gray-600 font-semibold">Tidak Diketahui</span>
                        @endif
                    <td class="px-6 py-4 text-center">
                        @if ($item->punishment && $item->punishment->potongan)
                            {{-- Jika ada potongan --}}
                            Rp {{ number_format($item->punishment->potongan, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- modal pop up menampilkan pilihan export excel --}}
    <div id="pop-up-modal-export-excel" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t  border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 ">
                        Export Rekap Absensi
                    </h3>
                    <button type="button"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center "
                        data-modal-hide="pop-up-modal-export-excel">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form class="p-4 md:p-5" action="rekap-absensi/export" method="GET" target="_blank">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 ">Category</label>
                            <select id="category" name="category"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                required>
                                <option selected="" value="">Select category</option>
                                <option value="hari">Hari</option>
                                <option value="bulan">Bulan</option>
                            </select>
                        </div>
                        {{-- <input type="date" name="date" id=""> --}}

                        {{-- date picker harian --}}
                        <div class="col-span-2 hidden" id="datepicker-hari">
                            <div class="relative max-w-sm">
                                {{-- <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 " aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                    </svg>
                                </div> --}}
                                <input id="datepicker-autohide" type="date" name="tanggalHari"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 "
                                    placeholder="Select date">
                            </div>
                        </div>

                        <div class="col-span-2 hidden" id="datepicker-bulan">
                            <input type="month" name="bulan" id="bulan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5"
                                placeholder="Pilih bulan">
                        </div>

                    </div>
                    <div class="coll-span-2 flex items-center justify-end">
                        <button type="submit"
                            class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">
                            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Export
                        </button>
                    </div>
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

    {{-- js interaksi export harian dan bulanan  --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category');
            const pickerHari = document.getElementById('datepicker-hari');
            const pickerBulan = document.getElementById('datepicker-bulan');

            categorySelect.addEventListener('change', function() {
                const selected = categorySelect.value;

                if (selected === 'hari') {
                    pickerHari.classList.remove('hidden');
                    pickerBulan.classList.add('hidden');
                } else if (selected === 'bulan') {
                    pickerHari.classList.add('hidden');
                    pickerBulan.classList.remove('hidden');
                } else {
                    // Jika opsi reset atau "Select category"
                    pickerHari.classList.add('hidden');
                    pickerBulan.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
