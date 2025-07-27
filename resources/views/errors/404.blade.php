<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Halaman Tidak Ditemukan - 404</title>
    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-C2THiBka.css') }}">
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-blue-100 h-screen flex items-center justify-center">

    <div
        class="max-w-md w-[800px] mx-4 sm:mx-6 text-center p-6 sm:p-10 bg-white rounded-3xl shadow-xl border-t-6 border-blue-400">
        <img src="{{ asset('assets/img/page-404.png') }}" alt="404 Page" class="mx-auto w-56 sm:w-64 mb-6" />

        <h1 class="text-3xl sm:text-4xl font-extrabold text-blue-700 mb-4">
            Oops! Halaman tidak ditemukan.
        </h1>

        <p class="text-base sm:text-lg text-gray-800 font-medium mb-2">
            Sepertinya kamu tersesat di antah berantah.
        </p>

        <p class="text-sm text-gray-600 italic mb-6">
            Halaman yang kamu cari mungkin sudah dipindahkan, dihapus, atau tidak pernah ada.
        </p>

        <div class="w-20 h-1 mx-auto bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full mb-6"></div>

        <p class="text-sm text-gray-500 mb-6">
            Silakan cek kembali URL atau kembali ke halaman utama.
        </p>
    </div>

</body>

</html>
