@extends('layouts.app')

@section('title', 'Pengaturan Profil - SIM LAB STTAL Admin')
@section('header', 'Pengaturan Profil')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">
        <form id="profileForm" action="{{ route('profile.update') }}" method="POST" class="space-y-6 validate">
            @csrf

            <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
                <div class="px-4 py-6 sm:p-8">

                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center gap-6 mb-10 pb-8 border-b border-gray-200">
                        <div class="relative group">
                            <div class="h-28 w-28 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-lg">
                                <img id="photoPreview"
                                    src="{{ $user->photo_path ? asset('storage/' . $user->photo_path) : asset('images/avatar-default.svg') }}"
                                    alt="Profile" class="h-full w-full object-cover" />
                            </div>
                            <button type="button" onclick="document.getElementById('fileInput').click()"
                                class="absolute bottom-0 right-0 bg-blue-600 p-2.5 rounded-full text-white shadow-sm hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                title="Ubah Foto Profil">
                                <i class="fa-solid fa-camera"></i>
                            </button>
                            <input type="file" id="fileInput" name="photo" accept="image/*" class="hidden"
                                onchange="previewImage(event)" />
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900" id="displayNama">{{ $user->nama }}</h2>
                            <div class="mt-1 flex items-center gap-2">
                                <span class="text-sm text-gray-500" id="displayNrp">NRP. {{ $user->nrp }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <!-- --- DATA PRIBADI --- -->
                        <div class="sm:col-span-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <i class="fa-solid fa-user w-5 mr-2 text-gray-400"></i> Data Pribadi
                            </h3>
                        </div>

                        <div class="sm:col-span-3">
                            <label htmlFor="nama" class="block text-sm font-medium leading-6 text-gray-900">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input type="text" name="nama" id="nama" required value="{{ $user->nama }}"
                                    class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label htmlFor="nrp" class="block text-sm font-medium leading-6 text-gray-900">
                                NRP <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-hashtag text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="nrp" id="nrp" required value="{{ $user->nrp }}"
                                    class="block w-full rounded-md border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label htmlFor="no_telepon" class="block text-sm font-medium leading-6 text-gray-900">
                                Nomor Telepon
                            </label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-phone text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="no_telepon" id="no_telepon" value="{{ $user->no_telepon }}"
                                    class="block w-full rounded-md border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-6 border-t border-gray-100 my-2"></div>

                        <!-- --- DATA KEDINASAN / AKADEMIK --- -->
                        <div class="sm:col-span-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <i class="fa-solid fa-briefcase w-5 mr-2 text-gray-400"></i> Data Kedinasan / Akademik
                            </h3>
                        </div>

                        <div class="sm:col-span-2">
                            <label htmlFor="pangkat" class="block text-sm font-medium leading-6 text-gray-900">
                                Pangkat
                            </label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input type="text" name="pangkat" id="pangkat" value="{{ $user->pangkat }}"
                                    class="block w-full rounded-md border-0 py-2.5 pl-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label htmlFor="korps" class="block text-sm font-medium leading-6 text-gray-900">
                                Korps
                            </label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-shield-halved text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="korps" id="korps" value="{{ $user->korps }}"
                                    class="block w-full rounded-md border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label htmlFor="jurusan" class="block text-sm font-medium leading-6 text-gray-900">
                                Jurusan
                            </label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-graduation-cap text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="jurusan" id="jurusan" value="{{ $user->jurusan }}"
                                    class="block w-full rounded-md border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-6 border-t border-gray-100 my-2"></div>

                        <!-- --- KEAMANAN --- -->
                        <div class="sm:col-span-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 flex items-center">
                                <i class="fa-solid fa-lock w-5 mr-2 text-gray-400"></i> Keamanan Akun
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Biarkan kosong jika Anda tidak ingin mengubah password saat ini.
                            </p>
                        </div>

                        <div class="sm:col-span-3">
                            <label htmlFor="password" class="block text-sm font-medium leading-6 text-gray-900">
                                Password Baru
                            </label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <input type="password" name="password" id="password" placeholder="••••••••"
                                    class="block w-full rounded-md border-0 py-2.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                    </div>
                </div>

                <!-- --- ACTION BUTTONS --- -->
                <div
                    class="flex items-center justify-end gap-x-4 border-t border-gray-900/10 px-4 py-4 sm:px-8 bg-gray-50 rounded-b-xl">
                    <button type="button"
                        class="text-sm font-semibold leading-6 text-gray-700 hover:text-gray-900 transition-colors px-4 py-2"
                        onclick="window.location.reload()">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmit"
                        class="rounded-md bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center transition-colors">
                        <i class="fa-solid fa-floppy-disk mr-2" id="iconSave"></i>
                        <i class="fa-solid fa-circle-notch fa-spin mr-2 hidden" id="iconLoading"></i>
                        <span id="btnText">Simpan Perubahan</span>
                    </button>
                </div>
            </div>
        </form>
    </main>
@endsection
@push('scripts')
    <script>
        document.getElementById('nama').addEventListener('input', function(e) {
            document.getElementById('displayNama').innerText = e.target.value || 'Nama Pengguna';
            document.getElementById('sidebarName').innerText = e.target.value || 'Nama Pengguna';
        });

        document.getElementById('nrp').addEventListener('input', function(e) {
            let val = e.target.value.trim();
            document.getElementById('displayNrp').innerText = val ? 'NRP. ' + val : 'NRP. -';
        });

        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxSize = 2 * 1024 * 1024;

                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran foto maksimal adalah 2 MB.'
                    });
                    input.value = '';
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                    if (document.querySelector('#sidebarAvatar img')) {
                        document.querySelector('#sidebarAvatar img').src = e.target.result;
                    }
                }

                reader.readAsDataURL(file);
            }
        }

        $(document).ready(function() {
            $.validator.addMethod('filesize', function(value, element, param) {
                return this.optional(element) || (element.files[0].size <= param);
            }, 'Ukuran file maksimal adalah 2 MB.');

            $('#profileForm').validate({
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
                        required: false,
                        minlength: 6,
                        maxlength: 100
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
                        maxlength: 15
                    },
                    photo: {
                        required: false,
                        extension: "jpg|jpeg|png",
                        filesize: 2097152
                    }

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
                        minlength: 'Minimal 6 karakter.',
                        maxlength: 'Maksimal 100 karakter.'
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
                        minlength: 'Minimal 10 karakter.',
                        maxlength: 'Maksimal 15 karakter.'
                    },
                    photo: {
                        extension: "Format file harus JPG, JPEG, atau PNG."
                    }
                },
                errorPlacement: function(error, element) {
                    var fieldId = element.attr('id');
                    $('#error-' + fieldId).text(error.text());
                }
            });

            $('#profileForm').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');
                let method = form.attr('method');
                let data = new FormData(this);

                if ($.fn.validate && form.hasClass('validate')) {
                    if (!form.valid()) return false;
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: data,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        $('#btnSubmit').prop('disabled', true);
                        $('#iconSave').addClass('hidden');
                        $('#iconLoading').removeClass('hidden');
                        $('#btnText').text('Menyimpan...');
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: res.message
                            });
                            if (res.errors) {
                                $('.error-text').text('');
                                $.each(res.errors, function(field, messages) {
                                    $('#error-' + field).text(messages[0]);
                                });
                            }
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat mengirim data.'
                        });
                    },
                    complete: function() {
                        $('#btnSubmit').prop('disabled', false);
                        $('#iconSave').removeClass('hidden');
                        $('#iconLoading').addClass('hidden');
                        $('#btnText').text('Simpan Perubahan');
                    }
                });
            });
        });
    </script>
@endpush
