@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Edit Karyawan</h3>
    <a href="/karyawan" class="inline-block text-gray-600 border-b border-gray-300 hover:text-blue-600">karyawan / <a
            href="{{ route('karyawan.edit', $editKaryawan->id) }}"
            class="inline-block text-blue-600 border-b border-gray-300"> Edit</a> </a>



    <form class=" w-full bg-amber-300a p-6 rounded-lg mt-5 shadow" action="{{ route('karyawan.update', $editKaryawan->id) }}"
        method="POST">
        {{-- Method spoofing for PUT/PATCH --}}
        @method('PUT')
        {{-- CSRF token for security --}}
        @csrf
        <div class="flex flex-col sm:flex-row sm:gap-4">
            <!-- Username -->
            <div class="mb-5 w-full">
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username</label>
                <input type="text" id="username" name="username"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="user1" required value="{{ $editKaryawan->username }}" />
                @error('username')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- <!-- Password -->
            <div class="mb-5 w-full">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                <input type="password" id="password" name="password"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="********" readonly />
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div> --}}
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-4">
            <!-- nama_lengkap -->
            <div class="mb-5 w-full">
                <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="Nama Lengkap" required value="{{ $editKaryawan->nama_lengkap }}" />
                @error('nama_lengkap')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- role -->
            <div class="w-full">
                <label for="role" class="block mb-2 text-sm font-medium text-gray-900 ">Role</label>
                <select id="role" name="role"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Select a role</option>
                    <option value="superadmin"
                        {{ old('role', $editKaryawan->role ?? '') == 'superadmin' ? 'selected' : 'hidden' }}>
                        Superadmin
                    </option>
                    <option value="hrd" {{ old('role', $editKaryawan->role ?? '') == 'hrd' ? 'selected' : '' }}>HRD
                    </option>
                    <option value="keuangan" {{ old('role', $editKaryawan->role ?? '') == 'keuangan' ? 'selected' : '' }}>
                        Keuangan</option>
                    <option value="user" {{ old('role', $editKaryawan->role ?? '') == 'user' ? 'selected' : '' }}>User
                    </option>

                </select>
                @error('role')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:gap-4">
            <!-- Gaji Pokok -->
            <div class="mb-5 w-full">
                <label for="gaji_pokok" class="block mb-2 text-sm font-medium text-gray-900">Gaji Pokok</label>
                <input type="number" id="gaji_pokok" name="gaji_pokok"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="000" required value="{{ $editKaryawan->gaji_pokok }}" />
                @error('gaji_pokok')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- potongan keterlambatan --}}
            <div class="mb-5 w-full">
                <label for="potongan_keterlambatan" class="block mb-2 text-sm font-medium text-gray-900">Potongan
                    Keterlambatan</label>
                <input type="number" id="potongan_keterlambatan" name="potongan_keterlambatan"
                    class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="000" required value="{{ $editKaryawan->potongan_keterlambatan }}" />
                @error('potongan_keterlambatan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Devisi -->
            <div class="w-full">
                <label for="devisi_id" class="block mb-2 text-sm font-medium text-gray-900 ">Devisi</label>
                <select id="devisi_id" name="devisi_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Devisi</option>
                    @foreach ($devisi as $devisiItem)
                        <option value="{{ $devisiItem->id }}" data-jam-mulai="{{ $devisiItem->jam_mulai }}"
                            data-jam-selesai="{{ $devisiItem->jam_selesai }}"
                            {{ old('devisi_id', $editKaryawan->devisi_id ?? '') == $devisiItem->id ? 'selected data-selected=true' : '' }}>
                            {{ $devisiItem->nama_devisi }} - {{ $devisiItem->nama_jamkerja }}
                        </option>
                    @endforeach
                </select>

                @error('devisi_id')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
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
                    placeholder="--" readonly />
            </div>

            <!-- jam akhir -->
            <div class="mb-5 w-full">
                <label for="jam_selesai" class="block mb-2 text-sm font-medium text-gray-600">Jam Selesai</label>
                <input type="text" id="jam_selesai"
                    class="bg-white border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                    placeholder="--" readonly />
            </div>
        </div>

        <div class="flex justify-end mt-2">
            <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg">
                Simpan
            </button>
        </div>

    </form>


    {{-- js --}}
    <script>
        const selectDevisi = document.getElementById('devisi_id');
        const inputJamMulai = document.getElementById('jam_mulai');
        const inputJamSelesai = document.getElementById('jam_selesai');

        function updateJamKerja(option) {
            inputJamMulai.value = option?.getAttribute('data-jam-mulai') || '';
            inputJamSelesai.value = option?.getAttribute('data-jam-selesai') || '';
        }

        // Saat load pertama (edit)
        const selected = selectDevisi.querySelector('option[data-selected="true"]');
        if (selected) {
            updateJamKerja(selected);
        }

        // Saat berubah
        selectDevisi.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            updateJamKerja(selectedOption);
        });
    </script>
@endsection
