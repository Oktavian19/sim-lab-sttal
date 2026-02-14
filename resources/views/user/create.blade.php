<form action="{{ url($role . '/store') }}" method="POST" id="form-tambah" class="w-full validate">
    @csrf

    <div class="relative w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h5 class="text-lg font-semibold text-gray-800">Tambah {{ ucfirst($role) }}</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="hideModal()">
                ✕
            </button>

        </div>

        <div class="px-6 py-4 space-y-4">
            <div>
                <label for="nrp" class="block text-sm font-medium text-gray-700">
                    NRP
                </label>
                <input type="text" name="nrp" id="nrp"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
                <p id="error-nrp" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">
                    Nama Lengkap
                </label>
                <input type="text" name="nama" id="nama"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
                <p id="error-nama" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <input type="password" name="password" id="password"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
                <p id="error-password" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label for="pangkat" class="block text-sm font-medium text-gray-700">
                    Pangkat
                </label>
                <input type="text" name="pangkat" id="pangkat"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
                <p id="error-pangkat" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label for="korps" class="block text-sm font-medium text-gray-700">
                    Korps
                </label>
                <input type="text" name="korps" id="korps"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
                <p id="error-korps" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label for="jurusan" class="block text-sm font-medium text-gray-700">
                    Jurusan
                </label>
                <input type="text" name="jurusan" id="jurusan"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
                <p id="error-jurusan" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label for="no_telepon" class="block text-sm font-medium text-gray-700">
                    Nomor Telepon
                </label>
                <input type="number" name="no_telepon" id="no_telepon"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
                <p id="error-no_telepon" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label for="photo_path" class="block text-sm font-medium text-gray-700">Foto Profil</label>
                <input type="file" name="photo_path" id="photo_path" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 border border-gray-300 rounded-md">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG. Maks: 2MB.</p>
                <p id="error-photo_path" class="mt-1 text-sm text-red-600"></p>
            </div>
        </div>

        <div class="flex justify-start gap-2 px-6 py-4 border-t">
            <button type="submit"
                class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Simpan
            </button>
            <button type="button" class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600"
                onclick="hideModal()">
                Batal
            </button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $.validator.addMethod('filesize', function(value, element, param) {
            if (element.files.length === 0) {
                return true;
            }

            return element.files[0].size <= param;
        }, 'Ukuran file terlalu besar.');

        $("#form-tambah").validate({
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
                photo_path: {
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
                    required: 'Password wajib diisi.',
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
                photo_path: {
                    extension: "Format file harus JPG, JPEG, atau PNG."
                }
            },
            errorPlacement: function(error, element) {
                let name = element.attr("id");
                $("#error-" + name).html(error);
            },
            highlight: function(element) {
                $(element).addClass("border-red-500 ring-red-200").removeClass("border-gray-300");
            },
            unhighlight: function(element) {
                $(element).removeClass("border-red-500 ring-red-200").addClass("border-gray-300");
            }
        });
    });
</script>
