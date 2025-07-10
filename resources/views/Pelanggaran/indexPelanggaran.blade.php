@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Pelanggaran</h3>
    <a href="/pelanggaran" class="inline-block text-blue-600 border-b border-gray-300">Pelanggaran / </a>

    {{-- {{$dataPelanggaran->toArray()}} --}}

    <div class="relative shadow-md sm:rounded-lg mt-5">
        {{-- dropdown --}}
        <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
            <div class="flex items-start space-x-4 flex-col md:items-start lg:items-center xl:items-center">
                {{-- Form Filter Tanggal --}}
                <form action="{{ route('pelanggaran.index') }}" method="GET"
                    class="space-y-4 gap-2 md:space-y-0 md:flex md:items-end md:gap-4 bg-white mb-4 rounded-xl ">

                    {{-- Input Tanggal --}}
                    <div class="flex-1">
                        <label for="tanggal_filter" class="block text-sm font-semibold text-gray-700 mb-1">
                            Filter Tanggal Pelanggaran:
                        </label>
                        <input type="date" id="tanggal_filter" name="tanggal_filter" value="{{ $selectedDate ?? '' }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-150 ease-in-out">
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
                        <a href="{{ route('pelanggaran.index') }}"
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

                {{-- <label for="table-search" class="sr-only">Search</label> --}}
                <div class="relative">
                    <div
                        class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500 " aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <input type="text" id="table-search"
                        class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-full bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Cari nama karyawan">
                </div>



            </div>

            {{-- tambah --}}
            {{-- <div class="md:mt-2">
                <a href="{{ route('karyawan.create') }}"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                    Tambah Karyawan
                    <i class="ms-2 fa-solid fa-plus"></i>
                </a>
            </div> --}}
        </div>

        {{-- table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500" id="pelanggaranTable">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Lengkap</th>
                        <th scope="col" class="px-6 py-3">Devisi</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Jam Terlambat</th>
                        <th scope="col" class="px-6 py-3">Potongan</th>
                        @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'hrd')
                            <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataPelanggaran as $item)
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
                                {{ $item->absensi->tanggal->format('d-m-Y') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->jam_keterlambatan }}
                            </td>

                            <td class="px-6 py-4">
                                @if ($item->potongan)
                                    Rp {{ number_format($item->potongan, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'hrd')
                                <td class="px-6 py-4 space-x-3 text-center">
                                    {{-- <a href="{{ route('pelanggaran.edit', $item->id) }}"
                                    class="font-medium text-blue-600 hover:underline">Detail</a> --}}
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


        <div class="px-6 pt-4 pb-2">
            {{ $dataPelanggaran->appends(request()->query())->links() }}
        </div>

    </div>


    {{-- modal delete data pelanggaran --}}
    @foreach ($dataPelanggaran as $item)
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
                    <form class="p-4 md:p-5 text-center" action="{{ route('pelanggaran.destroy', $item->id) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')

                        <svg class="mx-auto mb-4 text-yellow-400 w-12 h-12 " aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 ">Apakah kamu yakin ingin menghapus pelanggaran
                            <span class="font-semibold">{{ $item->user->nama_lengkap }}</span>
                            pada tanggal
                            <span class="font-semibold">{{ $item->absensi->tanggal->format('d-m-Y') }}</span>?
                        </h3>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('table-search');
            const rows = document.querySelectorAll('#pelanggaranTable tbody tr');

            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase();

                rows.forEach(row => {
                    const rowText = row.innerText.toLowerCase();

                    if (rowText.includes(keyword)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
