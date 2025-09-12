@extends('layouts.app')

@section('content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Check Out</h3>
    <a href="/absensi/check-out" class="inline-block text-blue-600 border-b border-gray-300">Check out /</a>

    <form class=" w-full bg-amber-300a p-6 rounded-lg mt-5 shadow" action="/absensi/check-outAksi" method="POST">
        @csrf
        @if ($sudahCheckOut)
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                ✅ Anda sudah melakukan <strong>Check-Out</strong> hari ini pada pukul
                {{ \Carbon\Carbon::parse($sudahCheckOut->waktu_keluar)->format('H:i') }}.
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

                <!-- Waktu Keluar -->
                <div class="mb-5 w-full">
                    <label for="waktu_keluar" class="block mb-2 text-sm font-medium text-gray-900">Waktu Keluar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="time" id="waktu_keluar" name="waktu_keluar"
                            class="bg-gray-50 border leading-none border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 "
                            min="" max="24:00" value="{{ now('Asia/Jakarta')->format('H:i') }}" readonly />
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:gap-4">
                <!-- latitude	 -->
                <div class="mb-5 w-full">
                    <label for="latitude" class="block mb-2 text-sm font-medium text-gray-900">Latitude</label>
                    <input type="text" id="latitude" name="latitude"
                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                        placeholder="" readonly />
                </div>
                {{-- @error('latitude')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror --}}

                <!-- longitude -->
                <div class="mb-5 w-full">
                    <label for="longitude" class="block mb-2 text-sm font-medium text-gray-900">Longitude</label>
                    <input type="text" id="longitude" name="longitude"
                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                        placeholder="" readonly />
                </div>
                {{-- @error('latitude')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror --}}


            </div>
            <div>
                <div id="map" class="w-full h-64 rounded-lg border border-gray-300 z-10 relative"></div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const mapDiv = document.getElementById('map');
            if (!mapDiv) {
                console.warn("Elemen #map tidak ditemukan.");
                return;
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;

                        document.getElementById('latitude').value = lat;
                        document.getElementById('longitude').value = lon;

                        const map = L.map('map').setView([lat, lon], 17);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '@Asa Group'
                        }).addTo(map);

                        L.marker([lat, lon]).addTo(map)
                            .bindPopup('Lokasi Anda')
                            .openPopup();
                    },
                    function(error) {
                        alert('Gagal mendapatkan lokasi: ' + error.message);
                    }, {
                        enableHighAccuracy: true, // paksa GPS
                        timeout: 10000, // maksimal 10 detik menunggu lokasi
                        maximumAge: 0 // jangan pakai cache lama
                    }
                );
            } else {
                alert('Geolocation tidak didukung browser ini.');
            }
        });
    </script>
@endsection
