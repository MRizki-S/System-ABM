@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Profile</h3>
    <a href="/profile" class="inline-block text-blue-600 border-b border-gray-300">Profile /</a>

    <form class=" w-full bg-amber-300a p-6 rounded-lg mt-5 shadow" action="/profile/update" method="POST">
        @csrf
        {{-- Method spoofing for PUT/PATCH --}}
        @method('PUT')


        <div class="mb-4">
            <h2 class="mb-2 text-lg font-semibold text-gray-900">Informasi:</h2>
            <ul class="max-w-md space-y-1 text-gray-500 list-inside">
                <li class="flex items-center">
                    <svg class="w-3.5 h-3.5 me-2 text-green-500 shrink-0" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                    </svg>
                    Anda hanya dapat mengubah informasi profil Anda, seperti username, nama lengkap, dan password.
                </li>
            </ul>
        </div>


        <div class="flex flex-col sm:flex-row sm:gap-4">
            <!-- Username -->
            <div class="mb-5 w-full">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                <input type="text" id="username" name="username"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="user1" required value="{{ $user->username }}" />
                @error('username')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- nama_lengkap -->
            <div class="mb-5 w-full">
                <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="Nama Lengkap" required value="{{ $user->nama_lengkap }}" />
                @error('nama_lengkap')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-4">
            <!-- Nama devisi - nama jam kerja  -->
            <div class="mb-5 w-full">
                <label for="devisi" class="block mb-2 text-sm font-medium text-gray-500">Devisi - Kerja</label>
                <input type="text" id="devisi" name="devisi"
                    class="bg-white border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="user1" required readonly
                    value="{{ $user->devisi->nama_devisi }} - {{ $user->devisi->nama_jamkerja }}" />
                @error('devisi')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- gaji pokok -->
            <div class="mb-5 w-full">
                <label for="gaji" class="block mb-2 text-sm font-medium text-gray-500">Gaji Pokok</label>
                <input type="text" id="gaji" name="gaji"
                    class="bg-white border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    required readonly
                    value="@if ($user->gaji_pokok) Rp {{ number_format($user->gaji_pokok, 0, ',', '.') }}
                                @else
                                    - @endif" />
                @error('gaji_pokok')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- {{{$user}}} --}}
            {{-- potongan keterlambatan --}}
            <div class="mb-5 w-full">
                <label for="potongan_keterlambatan" class="block mb-2 text-sm font-medium text-gray-500">Potongan Keterlambatan Pada satu hari</label>
                <input type="text" id="potongan_keterlambatan" name="potongan_keterlambatan"
                    class="bg-white border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    required readonly
                    value="@if ($user->potongan_keterlambatan) Rp {{ number_format($user->potongan_keterlambatan, 0, ',', '.') }}
                                @else
                                    - @endif" />
                @error('potongan_keterlambatan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


        </div>

        {{-- Jam kerja --}}
        <h3 class="text-lg font-medium text-gray-600 mb-2 w-full">Jam Kerja</h3>
        <div class="flex flex-col sm:flex-row sm:gap-4">
            {{-- title jam kerja --}}
            <!-- jam mulai -->
            <div class="mb-5 w-full">
                <label for="jam_mulai" class="block mb-2 text-sm font-medium text-gray-500">Jam Mulai</label>
                <input type="text" id="jam_mulai"
                    class="bg-white border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    readonly value="{{ $user->devisi->jam_mulai }}" />
            </div>

            <!-- jam akhir -->
            <div class="mb-5 w-full">
                <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-600">Jam Selesai</label>
                <input type="text" id="jam_selesai"
                    class="bg-white border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    readonly value="{{ $user->devisi->jam_selesai }}" />
            </div>
        </div>

        {{-- Change password --}}
        <h3 class="text-lg font-medium text-gray-600 mb-2 w-full">Ubah Password</h3>
        <div class="flex flex-col sm:flex-row sm:gap-4">
            <!-- Password -->
            <div class="mb-5 w-full">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" id="password" name="password"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="********" />
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Konfirmasi Password -->
            <div class="mb-5 w-full">
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Konfirmasi
                    Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="********" />
                @error('password_confirmation')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end mt-2">
            <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg">
                Simpan
            </button>
        </div>

    </form>
@endsection
