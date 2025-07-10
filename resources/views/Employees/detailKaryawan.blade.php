@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Detail Karyawan</h3>
    <a href="/karyawan" class="inline-block text-gray-600 border-b border-gray-300 hover:text-blue-600">karyawan /
        <a href="{{ route('karyawan.show', $detailKaryawan->id) }}"
            class="inline-block text-blue-600 border-b border-gray-300"> Detail
        </a>
    </a>



    <div class=" w-full bg-amber-300a p-6 rounded-lg mt-5 sm:gap-4 shadow-xl sm:rounded-lg">
        <!-- Username -->
        <div class="mb-5 w-full">
            <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
            <input type="text" id="username" name="username"
                class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                placeholder="user1" value="{{ $detailKaryawan->username }}" readonly />
            @error('username')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-4">
            <!-- nama_lengkap -->
            <div class="mb-5 w-full">
                <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="Nama Lengkap" required readonly value="{{ $detailKaryawan->nama_lengkap }}" />
            </div>

            <!-- role -->
            <div class="w-full">
                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 ">Role</label>
                <input type="text" id="role" name="role"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="Nama Lengkap" required readonly value="{{ $detailKaryawan->role }}" />
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-4">
            <!-- Gaji Pokok -->
            <div class="mb-5 w-full">
                <label for="gaji_pokok" class="block mb-2 text-sm font-medium text-gray-900">Gaji Pokok</label>
                <input type="number" id="gaji_pokok" name="gaji_pokok"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="000" required readonly value="{{ $detailKaryawan->gaji_pokok }}" />
            </div>

            {{-- potongan keterlambatan --}}
            <div class="mb-5 w-full">
                <label for="potongan_keterlambatan" class="block mb-2 text-sm font-medium text-gray-900">Potongan
                    Keterlambatan</label>
                <input type="number" id="potongan_keterlambatan" name="potongan_keterlambatan"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="000" required readonly value="{{ $detailKaryawan->potongan_keterlambatan }}" />
            </div>

            <!-- Devisi -->
            <div class="w-full">
                <label for="devisi_id" class="block mb-2 text-sm font-medium text-gray-900 ">Devisi</label>
                <input type="text" id="devisi->nama_devisi" name="devisi->nama_devisi"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="Nama Lengkap" required readonly value="{{ $detailKaryawan->devisi->nama_devisi }}" />
            </div>
        </div>

        <h3 class="text-lg font-semibold text-gray-600 mb-2 w-full">Jam Kerja</h3>
        <div class="flex flex-col sm:flex-row sm:gap-4">
            {{-- title jam kerja --}}
            <!-- jam mulai -->
            <div class="mb-5 w-full">
                <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-600">Jam Mulai</label>
                <input type="text" id="jam_mulai"
                    class="bg-white border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    value="{{ $detailKaryawan->devisi->jam_mulai }}" readonly />
            </div>

            <!-- jam akhir -->
            <div class="mb-5 w-full">
                <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-600">Jam Selesai</label>
                <input type="text" id="jam_selesai"
                    class="bg-white border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    value="{{ $detailKaryawan->devisi->jam_selesai }}" readonly />
            </div>
        </div>

        {{-- table history keterlambatan bulan ini --}}
        <h3 class="text-lg font-semibold text-gray-600 mt-6 border-t border-gray-300 pt-2 w-full">Riwayat Absensi Terlambat Bulan Ini</h3>
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
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Jam Keterlambatan
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                            </svg>
                        </span>
                    </th>
                    <th>
                        <span class="flex items-center">
                            Potongan
                            <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
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
                @foreach ($detailKaryawan->punishments as $dataAbsensi)
                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                        {{-- Nama Lengkap --}}
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            {{ optional($dataAbsensi->user)->nama_lengkap ?? '-' }}
                        </td>

                        {{-- Tanggal (dari relasi absensi) --}}
                        <td class="px-6 py-4">
                            {{ optional($dataAbsensi->absensi)?->tanggal
                                ? \Carbon\Carbon::parse($dataAbsensi->absensi->tanggal)->format('d-m-Y')
                                : '-' }}
                        </td>

                        {{-- Jam Keterlambatan --}}
                        <td class="px-6 py-4">
                            {{ $dataAbsensi->jam_keterlambatan ?? '-' }}
                        </td>

                        {{-- Potongan --}}
                        <td class="px-6 py-4">
                            Rp{{ number_format($dataAbsensi->potongan ?? 0, 0, ',', '.') }}
                        </td>

                        {{-- Jenis Absen --}}
                        <td class="px-6 py-4">
                            @php $jenis = optional($dataAbsensi->absensi)->jenis; @endphp

                            @if ($jenis === 'check_in')
                                <span class="text-green-600 font-semibold">Check In</span>
                            @elseif ($jenis === 'check_out')
                                <span class="text-blue-600 font-semibold">Check Out</span>
                            @elseif ($jenis === 'sakit')
                                <span class="text-yellow-600 font-semibold">Sakit</span>
                            @elseif ($jenis === 'izin')
                                <span class="text-red-600 font-semibold">Izin</span>
                            @else
                                <span class="text-gray-500 italic">-</span>
                            @endif
                        </td>
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
