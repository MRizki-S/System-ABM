@extends('layouts.app')

@section('content')
    @php
        use Carbon\Carbon;

        $bulanNow = Carbon::now()->startOfMonth();
        $startOfMonth = $bulanNow->copy()->subMonth()->day(26); // 26 bulan sebelumnya
        $endOfMonth = $bulanNow->copy()->day(25); // 25 bulan ini
    @endphp
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Dashboard</h3>
    <a href="/dashboard" class="inline-block text-blue-600 border-b border-gray-300">Dashboard /</a>

    <div class="mt-4 space-y-2 text-gray-700 border-b border-gray-200
    ">
        <p class="text-2xl font-medium">👋 Selamat datang, <span
                class="font-semibold text-blue-600">{{ $user->nama_lengkap }}</span>!</p>
    </div>

    <h4 class="text-xl md:text-2xl font-semibold text-gray-800 mt-8 mb-2 ">
        📊 Ringkasan Absensi Bulan Ini - <span class="text-blue-600">{{ $bulanIni }}</span>
    </h4>
    {{-- card --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-5 gap-3 mt-6 mb-4">
        {{-- Template Card --}}
        <div
            class="bg-gradient-to-br from-white to-gray-50 shadow-sm hover:shadow-md transition duration-300 rounded-xl p-4 flex items-center justify-between border border-gray-100 text-sm">
            <div class="space-y-1">
                <h4 class="text-gray-700 font-medium">Masuk Hari Ini</h4>
                <p class="text-xl font-bold text-green-600">{{ $jamMasukHariIni }}</p>
                {{-- <p class="text-xs text-green-500 italic">Datang lebih awal</p> --}}
            </div>
            <div class="flex items-center justify-center bg-green-100 p-2.5 rounded-full shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="currentColor"
                    viewBox="0 0 512 512">
                    <path
                        d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" />
                </svg>
            </div>
        </div>

        {{-- Hadir --}}
        <div
            class="bg-white shadow-sm hover:shadow-md transition rounded-xl p-4 flex items-center justify-between border border-gray-100 text-sm">
            <div>
                <h4 class="text-gray-700 font-medium">Hadir Bulan Ini</h4>
                <p class="text-xl font-bold text-blue-600">{{ $totalHadir }}</p>
            </div>
            <div class="flex items-center justify-center bg-blue-100 p-2.5 rounded-full shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="currentColor"
                    viewBox="0 0 640 512">
                    <path
                        d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4zm323-128.4l-27.8-28.1c-4.6-4.7-12.1-4.7-16.8-.1l-104.8 104-45.5-45.8c-4.6-4.7-12.1-4.7-16.8-.1l-28.1 27.9c-4.7 4.6-4.7 12.1-.1 16.8l81.7 82.3c4.6 4.7 12.1 4.7 16.8 .1l141.3-140.2c4.6-4.7 4.7-12.2 .1-16.8z" />
                </svg>
            </div>
        </div>

        {{-- Sakit --}}
        <div
            class="bg-white shadow-sm hover:shadow-md transition rounded-xl p-4 flex items-center justify-between border border-gray-100 text-sm">
            <div>
                <h4 class="text-gray-700 font-medium">Sakit Bulan Ini</h4>
                <p class="text-xl font-bold text-yellow-500">{{ $totalSakit }}</p>
            </div>
            <div class="flex items-center justify-center bg-yellow-100 p-2.5 rounded-full shadow-inner">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-6 h-6 text-yellow-500"
                    fill="currentColor">
                    <path
                        d="M128 244v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12zm140 12h40c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12zm-76 84v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm76 12h40c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12zm180 124v36H0v-36c0-6.6 5.4-12 12-12h19.5V85C31.5 73.4 42.2 64 55.5 64H144V24c0-13.3 10.7-24 24-24h112c13.3 0 24 10.7 24 24v40h88.5c13.3 0 24 9.4 24 21V464H436c6.6 0 12 5.4 12 12zM79.5 463H192v-67c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v67h112.5V112H304v24c0 13.3-10.7 24-24 24H168c-13.3 0-24-10.7-24-24v-24H79.5v351zM266 64h-26V38a6 6 0 0 0 -6-6h-20a6 6 0 0 0 -6 6v26h-26a6 6 0 0 0 -6 6v20a6 6 0 0 0 6 6h26v26a6 6 0 0 0 6 6h20a6 6 0 0 0 6-6V96h26a6 6 0 0 0 6-6V70a6 6 0 0 0 -6-6z" />
                </svg>
            </div>
        </div>

        {{-- Izin --}}
        <div
            class="bg-white shadow-sm hover:shadow-md transition rounded-xl p-4 flex items-center justify-between border border-gray-100 text-sm">
            <div>
                <h4 class="text-gray-700 font-medium">Izin Bulan Ini</h4>
                <p class="text-xl font-bold text-purple-600">{{ $totalIzin }}</p>
            </div>
            <div class="flex items-center justify-center bg-purple-100 p-2.5 rounded-full shadow-inner">
                <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 512 512">
                    <path
                        d="M444.5 3.5L28.7 195.4c-48 22.4-32 92.8 19.2 92.8h175.9v175.9c0 51.2 70.4 67.2 92.8 19.2l191.9-415.8c16-38.4-25.6-80-64-64z" />
                </svg>
            </div>
        </div>

        {{-- Terlambat --}}
        <div
            class="bg-white shadow-sm hover:shadow-md transition rounded-xl p-4 flex items-center justify-between border border-gray-100 text-sm">
            <div>
                <h4 class="text-gray-700 font-medium">Terlambat Bulan Ini</h4>
                <p class="text-xl font-bold text-red-600">{{ $totalTerlambat }}</p>
            </div>
            <div class="flex items-center justify-center bg-red-100 p-2.5 rounded-full shadow-inner">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 576 512">
                    <path
                        d="M256 32c14.2 0 27.3 7.5 34.5 19.8l216 368c7.3 12.4 7.3 27.7 .2 40.1S486.3 480 472 480L40 480c-14.3 0-27.6-7.7-34.7-20.1s-7-27.8 .2-40.1l216-368C228.7 39.5 241.8 32 256 32zm0 128c-13.3 0-24 10.7-24 24l0 112c0 13.3 10.7 24 24 24s24-10.7 24-24l0-112c0-13.3-10.7-24-24-24zm32 224a32 32 0 1 0 -64 0 32 32 0 1 0 64 0z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- table rekap absensi bulan ini --}}
    <div class="shadow-xl sm:rounded-lg mt-5 p-2">
        <table id="default-table" class="shadow-2xl">
            <thead>
                <tr>
                    <th>
                        <span class="flex items-center">
                            Nama Lengkap
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th data-type="date" data-format="DD-MM-YYYY">
                        <span class="flex items-center">
                            Tanggal
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Jam Masuk
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Jam Keluar
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Absen
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataRekapAbsensi as $dataAbsensi)
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ $dataAbsensi->user->nama_lengkap }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $dataAbsensi->tanggal->format('d-m-Y') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $dataAbsensi->waktu_masuk ? $dataAbsensi->waktu_masuk : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $dataAbsensi->waktu_keluar ? $dataAbsensi->waktu_keluar : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($dataAbsensi->jenis == 'check_in')
                                <span class="text-green-600 font-semibold">Check In</span>
                            @elseif ($dataAbsensi->jenis == 'check_out')
                                <span class="text-blue-600 font-semibold">Check Out</span>
                            @elseif ($dataAbsensi->jenis == 'sakit')
                                <span class="text-yellow-600 font-semibold">Sakit</span>
                            @elseif ($dataAbsensi->jenis == 'izin')
                                <span class="text-red-600 font-semibold">Izin</span>
                            @else
                                <span class="text-gray-600 font-semibold"></span>
                            @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        if (document.getElementById("default-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#default-table", {
                searchable: false,
                perPageSelect: false
            });
        }
    </script>
@endsection
