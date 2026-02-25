<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIM LAB STTAL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body
    class="bg-gray-100 font-sans antialiased min-h-screen flex items-center justify-center relative overflow-x-hidden py-10">
    <div class="absolute top-0 left-0 w-full h-1/2 bg-slate-800 z-0"></div>
    <div class="absolute bottom-0 right-0 text-slate-200 opacity-10 pointer-events-none z-0">
        <i class="fa-solid fa-anchor text-[400px] -mb-20 -mr-20"></i>
    </div>

    <div class="relative z-10 w-full max-w-3xl bg-white rounded-lg shadow-2xl overflow-hidden mx-4 my-8">

        <div class="bg-slate-900 p-6 text-center border-b-4 border-blue-500">
            <div
                class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-800 text-blue-400 mb-2 shadow-inner">
                <img src="{{ asset('images/logo.png') }}" alt="Icon" class="w-20 h-20 object-contain">
            </div>
            <h2 class="text-xl font-bold text-white tracking-wider">REGISTRASI <span class="text-blue-500">STTAL</span>
            </h2>
            <p class="text-slate-400 text-xs mt-1">Buat Akun Sistem Informasi Laboratorium</p>
        </div>

        <div class="p-8">
            <form action="{{ route('postRegister') }}" method="POST" id="form-register">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <span class="block sm:inline text-sm">
                            @foreach ($errors->all() as $error)
                                <div>- {{ $error }}</div>
                            @endforeach
                        </span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-5">
                        <div>
                            <label for="nrp" class="block text-sm font-medium text-slate-700 mb-1">NRP /
                                NIP</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-id-card"></i>
                                </div>
                                <input type="text" id="nrp" name="nrp"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                                    placeholder="NRP / NIP" required>
                            </div>
                            <p id="error-nrp" class="mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        <div>
                            <label for="nama" class="block text-sm font-medium text-slate-700 mb-1">Nama
                                Lengkap</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <input type="text" id="nama" name="nama"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                                    placeholder="Nama Lengkap" required>
                            </div>
                            <p id="error-nama" class="mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        <div>
                            <label for="pangkat" class="block text-sm font-medium text-slate-700 mb-1">Pangkat</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-medal"></i>
                                </div>
                                <input type="text" id="pangkat" name="pangkat"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                                    placeholder="Contoh: Letkol" required>
                            </div>
                            <p id="error-pangkat" class="mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        <div>
                            <label for="korps" class="block text-sm font-medium text-slate-700 mb-1">Korps</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <input type="text" id="korps" name="korps"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                                    placeholder="Contoh: Elektronika (E)" required>
                            </div>
                            <p id="error-korps" class="mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label for="jurusan" class="block text-sm font-medium text-slate-700 mb-1">Jurusan</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                                <input type="text" id="jurusan" name="jurusan"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                                    placeholder="Contoh: S1 Teknik Elektro" required>
                            </div>
                            <p id="error-jurusan" class="mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        <div>
                            <label for="no_telepon" class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon
                                / WA</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <input type="number" id="no_telepon" name="no_telepon"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                                    placeholder="0812xxxx" required>
                            </div>
                            <p id="error-no_telepon" class="mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <input :type="show ? 'text' : 'password'" id="password" name="password"
                                    class="block w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                                    placeholder="Buat password" required>
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                    <i class="fa-regular" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                                </button>
                            </div>
                            <p id="error-password" class="mt-1 text-xs text-red-600 font-semibold"></p>
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                            <div class="relative">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-shadow"
                                    placeholder="Ulangi password" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-100 pt-6">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
                        DAFTAR SEKARANG <i class="fa-solid fa-user-plus ml-2 mt-0.5"></i>
                    </button>
                    <div class="mt-4 text-center">
                        <span class="text-sm text-slate-600">Sudah punya akun? </span>
                        <a href="{{ route('login') }}"
                            class="font-medium text-sm text-blue-600 hover:text-blue-500 transition-colors">
                            Login Disini
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex justify-center">
            <p class="text-xs text-slate-500 text-center">
                &copy; 2026 STTAL. Sekolah Tinggi Teknologi Angkatan Laut.
            </p>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#form-register").validate({
                rules: {
                    nrp: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 100
                    },
                    password_confirmation: {
                        equalTo: "#password"
                    },
                    pangkat: {
                        required: true,
                        minlength: 2,
                        maxlength: 50
                    },
                    korps: {
                        required: true,
                        minlength: 2,
                        maxlength: 50
                    },
                    jurusan: {
                        required: true,
                        minlength: 2,
                        maxlength: 50
                    },
                    no_telepon: {
                        required: true,
                        minlength: 10,
                        maxlength: 15,
                        number: true
                    },
                },
                messages: {
                    nrp: {
                        required: 'NRP wajib diisi.',
                        minlength: 'Minimal 3 karakter.',
                        maxlength: 'Maksimal 100 karakter.'
                    },
                    nama: {
                        required: 'Nama wajib diisi.',
                        minlength: 'Minimal 3 karakter.',
                        maxlength: 'Maksimal 100 karakter.'
                    },
                    password: {
                        required: 'Password wajib diisi.',
                        minlength: 'Minimal 6 karakter.',
                        maxlength: 'Maksimal 100 karakter.'
                    },
                    password_confirmation: {
                        equalTo: 'Password tidak cocok.'
                    },
                    pangkat: {
                        required: 'Pangkat wajib diisi.',
                        minlength: 'Minimal 2 karakter.',
                        maxlength: 'Maksimal 50 karakter.'
                    },
                    korps: {
                        required: 'Korps wajib diisi.',
                        minlength: 'Minimal 2 karakter.',
                        maxlength: 'Maksimal 50 karakter.'
                    },
                    jurusan: {
                        required: 'Jurusan wajib diisi.',
                        minlength: 'Minimal 2 karakter.',
                        maxlength: 'Maksimal 50 karakter.'
                    },
                    no_telepon: {
                        required: 'No. Telepon wajib diisi.',
                        minlength: 'Minimal 10 digit.',
                        maxlength: 'Maksimal 15 digit.',
                        number: 'Hanya boleh angka.'
                    },
                },
                errorPlacement: function(error, element) {
                    let name = element.attr("name");
                    $("#error-" + name).html(error);
                },
                highlight: function(element) {
                    $(element).addClass(
                        "border-red-500 ring-1 ring-red-200 focus:border-red-500 focus:ring-red-200"
                    ).removeClass("border-slate-300 focus:ring-blue-500");
                },
                unhighlight: function(element) {
                    $(element).removeClass(
                        "border-red-500 ring-1 ring-red-200 focus:border-red-500 focus:ring-red-200"
                    ).addClass("border-slate-300 focus:ring-blue-500");
                    let name = $(element).attr("name");
                    $("#error-" + name).empty();
                },
                submitHandler: function(form) {
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: $(form).attr('action'),
                        type: 'POST',
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registrasi Berhasil',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    allowOutsideClick: true
                                }).then(() => {
                                    window.location.href = "{{ route('login') }}";
                                });
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan pada server.';

                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                errorMessage = '';
                                $.each(errors, function(key, value) {
                                    errorMessage += value[0] + '<br>';

                                    $("#error-" + key).html(value[0]);
                                    $(`[name="${key}"]`).addClass(
                                        "border-red-500 ring-1 ring-red-200");
                                });
                            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                html: errorMessage,
                            });
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>

</html>
