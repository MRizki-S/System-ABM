<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login Page</title>
    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-Cx_wZTzM.css') }}">
    <script src="{{ asset('build/assets/app-DNxiirP_.js') }}"></script>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    {{-- favicon --}}
    <link rel="icon" href="{{ asset('assets/img/logo-abm2.png') }}" type="image/x-icon">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex flex-col md:flex-row min-h-screen">
        <!-- Left Section -->
        <div
            class="hidden md:flex md:w-[40%] lg:w-[60%] bg-white flex-col justify-center items-center p-8 gradient-bg text-white">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">Welcome Back!</h1>
                <p class="text-lg font-medium mb-6">Access your account to continue</p>
                <img src="{{ asset('assets/img/logo-abm2-up.png') }}" alt="Login Illustration" class="rounded-full  w-3/4 mx-auto">
            </div>
        </div>

        <!-- Right Section -->
        <div class="w-full min-h-screen md:w-[60%] lg:w-[40%] flex flex-col justify-center items-center p-8 bg-white">
            <div class="w-full max-w-md">
                <div class="flex justify-center mb-6 md:hidden">
                    <img src="{{ asset('assets/img/logo-abm2.png') }}" alt="ABM Logo" class="h-20 w-auto">
                </div>
                <h2 class="text-3xl font-bold text-blue-700 mb-4 text-center md:text-start lg:text-start xl:text-start">
                    Log in</h2>
                <p class="text-gray-600 mb-6">Enter your credentials to access your account</p>
                <form action="/aksiLogin" method="post">
                    @csrf
                    @if (Session::has('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                            role="alert">
                            <span class="block sm:inline">{{ Session::get('success') }}</span>
                            <button class="absolute top-0 bottom-0 right-0 px-4 py-3"
                                onclick="this.parentElement.remove();">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if (Session::has('error'))
                        <div class="bg-red-100 mb-2 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <span class="block sm:inline">{{ Session::get('error') }}</span>
                            <button class="absolute top-0 bottom-0 right-0 px-4 py-3"
                                onclick="this.parentElement.remove();">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700" for="email">Username</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                class="w-4 h-4 me-3 absolute top-3 left-3 text-gray-500 transition durat
                            ion-75  group-hover:text-gray-900 "
                                fill="currentColor">
                                <path
                                    d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z" />
                            </svg>
                            <input
                                class="w-full px-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                id="username" name="username" placeholder="user1" type="text"
                                value="{{ old('username') }}" />
                            @error('username')
                                <p class=" text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700" for="password">Password</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                class="w-4 h-4 me-3 absolute top-3 left-3 text-gray-500 transition durat
                            ion-75  group-hover:text-gray-900 "
                                fill="currentColor">
                                <path
                                    d="M144 144l0 48 160 0 0-48c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192l0-48C80 64.5 144.5 0 224 0s144 64.5 144 144l0 48 16 0c35.3 0 64 28.7 64 64l0 192c0 35.3-28.7 64-64 64L64 512c-35.3 0-64-28.7-64-64L0 256c0-35.3 28.7-64 64-64l16 0z" />
                            </svg>
                            <input
                                class="w-full px-10 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                                id="password" name="password" placeholder="••••••••" type="password"
                                value="{{ old('password') }}" />
                            @error('password')
                                <p class=" text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button
                        class="w-full py-2 rounded-lg text-white font-semibold transition duration-200 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-indigo-600 hover:to-blue-600"
                        type="submit">
                        Log in
                    </button>

                    {{-- <p class="mt-4 text-center text-gray-500 italic">

                    </p> --}}

                    <blockquote class="mt-4 text-center text-xl italic text-gray-500 ">
                        <p>"Tepat waktu adalah bentuk sederhana dari tanggung jawab."</p>
                    </blockquote>

                </form>
            </div>
        </div>
    </div>
</body>

</html>
