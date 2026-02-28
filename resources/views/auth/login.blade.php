<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIM LAB STTAL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>

<body class="bg-gray-100 font-sans antialiased h-screen flex items-center justify-center relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-1/2 bg-slate-800 z-0"></div>
    <div class="absolute bottom-0 right-0 text-slate-200 opacity-10 pointer-events-none z-0">
        <i class="fa-solid fa-anchor text-[400px] -mb-20 -mr-20"></i>
    </div>

    <!-- Login Card -->
    <div class="relative z-10 w-full max-w-md bg-white rounded-lg shadow-2xl overflow-hidden mx-4">

        <!-- Header Section -->
        <div class="bg-slate-900 p-8 text-center border-b-4 border-blue-500">
            <div
                class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-800 text-blue-400 mb-4 shadow-inner">
                <img src="{{ asset('images/logo.png') }}" alt="Icon" class="w-20 h-20 object-contain">
            </div>
            <h2 class="text-2xl font-bold text-white tracking-wider">SIM LAB <span class="text-blue-500">STTAL</span>
            </h2>
            <p class="text-slate-400 text-sm mt-2">Sistem Informasi Administrasi Laboratorium</p>
        </div>

        <!-- Form Section -->
        <div class="p-8">
            <form action="{{ route('postLogin') }}" method="POST">
                @csrf

                <!-- Alert Error -->
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline text-sm">
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </span>
                    </div>
                @endif

                <!-- NRP / Username Input -->
                <div class="mb-5">
                    <label for="nrp" class="block text-sm font-medium text-slate-700 mb-1">NRP / NIP</label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <input type="text" id="nrp" name="nrp"
                            class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                            placeholder="Masukkan NRP/NIP Anda" required autofocus>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">
                        Password
                    </label>

                    <div class="relative" x-data="{ show: false }">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>

                        <input :type="show ? 'text' : 'password'" id="password" name="password"
                            class="block w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                            placeholder="Masukkan password" required>

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'">
                            </i>
                        </button>
                    </div>
                </div>


                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember" class="ml-2 block text-sm text-slate-600 cursor-pointer">
                            Ingat Saya
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="{{ route('register') }}"
                            class="font-medium text-blue-600 hover:text-blue-500 transition-colors">
                            Belum Punya Akun?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                    MASUK <i class="fa-solid fa-arrow-right-to-bracket ml-2 mt-0.5"></i>
                </button>
            </form>
        </div>

        <!-- Footer Card -->
        <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex justify-center">
            <p class="text-xs text-slate-500 text-center">
                &copy; 2026 STTAL. Sekolah Tinggi Teknologi Angkatan Laut.<br>All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
