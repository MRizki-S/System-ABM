@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Absen</h3>
    <a href="/absensi/absen" class="inline-block text-blue-600 border-b border-gray-300">Absen /</a>

    <form class=" w-full bg-amber-300a p-6 rounded-lg mt-5 shadow" action="/absensi/absenAksi" method="POST">
        @csrf

        @if ($sudahAbsenHariIni)
            <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50">
                <strong>Anda sudah melakukan absen ketidakhadiran hari ini dengan alasan
                    {{ $sudahAbsenHariIni->jenis }}.</strong>
            </div>
        @else
            <div class="flex flex-col sm:flex-row sm:gap-4">
                <!-- Tanggal     -->
                <div class="mb-5 w-full">
                    <label for="tanggal" class="block mb-2 text-sm font-medium text-gray-900">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal"
                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                        placeholder="" required value="{{ now('Asia/Jakarta')->toDateString() }}" readonly />
                </div>

                <!-- jenis -->
                <div class="w-full">
                    <label for="jenis" class="block mb-2 text-sm font-medium text-gray-900 ">Jenis</label>
                    <select id="jenis" name="jenis" required
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="sakit" {{ old('jenis') }}>Sakit</option>
                        <option value="izin" {{ old('jenis') }}>Izin</option>
                    </select>
                    @error('jenis')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="">
                <label for="keterangan" class="block mb-2 text-sm font-medium text-gray-900">
                    Keterangan
                    <span class="block text-xs font-normal text-gray-500 mt-1">
                        *Kolom keterangan tidak boleh lebih dari 40 karakter.
                    </span>
                </label>

                <textarea id="keterangan" name="keterangan" rows="2" required maxlength="40"
                    class="resize-none block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border @error('ketarangan') border-red-500 focus:border-red-500 focus:ring-red-500
                    @else border-gray-300 focus:border-blue-500 focus:ring-blue-500 @enderror"
                    placeholder="Tulis keterangan anda disini..."></textarea>
                @error('keterangan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end mt-2">
                <button type="submit"
                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg">
                    Simpan
                </button>
            </div>
        @endif
    </form>

    <script>
        document.querySelector('form').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';
        });
    </script>
@endsection
