<form action="{{ url('alat/' . $alat->id . '/update') }}" method="POST" id="form-edit" class="w-full validate">
    @csrf

    <div class="relative w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h5 class="text-lg font-semibold text-gray-800">Edit Alat</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="hideModal()">
                ✕
            </button>

        </div>

        <div class="px-6 py-4 space-y-4">
            <div>
                <label for="nama_alat" class="block text-sm font-medium text-gray-700">
                    Nama Alat
                </label>
                <input type="text" name="nama_alat" id="nama_alat"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required value="{{ $alat->nama_alat }}">
                <p id="error-nama_alat" class="mt-1 text-sm text-red-600"></p>
            </div>

            <div>
                <label for="merk" class="block text-sm font-medium text-gray-700">
                    Merek
                </label>
                <input type="text" name="merk" id="merk"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required value="{{ $alat->merk }}">
                <p id="error-merk" class="mt-1 text-sm text-red-600"></p>
            </div>

            <div>
                <label for="tahun_pengadaan" class="block text-sm font-medium text-gray-700">
                    Tahun Pengadaan
                </label>
                <input type="number" name="tahun_pengadaan" id="tahun_pengadaan"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required value="{{ $alat->tahun_pengadaan }}">
                <p id="error-tahun_pengadaan" class="mt-1 text-sm text-red-600"></p>
            </div>

            <div>
                <label for="lokasi" class="block text-sm font-medium text-gray-700">
                    Lokasi (Lab)
                </label>
                <select name="lokasi" id="lokasi"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                   focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="">-- Pilih Laboratorium --</option>
                    @foreach ($lab as $laboratorium)
                        <option value="{{ $laboratorium->id }}"
                            {{ $alat->lokasi == $laboratorium->id ? 'selected' : '' }}>{{ $laboratorium->nama_lab }}
                        </option>
                    @endforeach
                </select>
                <p id="error-lokasi" class="mt-1 text-sm text-red-600"></p>
            </div>

                        <div>
                <label for="status_kondisi" class="block text-sm font-medium text-gray-700">
                    Kondisi Alat
                </label>
                <select name="status_kondisi" id="status_kondisi"
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm
                   focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="baik" {{ $alat->status_kondisi == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ $alat->status_kondisi == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ $alat->status_kondisi == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
                <p id="error-lokasi" class="mt-1 text-sm text-red-600"></p>
            </div>

            <div>
                <label for="foto_alat" class="block text-sm font-medium text-gray-700">Foto Alat</label>
                <input type="file" name="foto_alat" id="foto_alat" accept="image/*"
                    class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 border border-gray-300 rounded-md">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG. Maks: 2MB.</p>
                <p id="error-foto_alat" class="mt-1 text-sm text-red-600"></p>
            </div>
        </div>

        <div class="flex justify-start gap-2 px-6 py-4 border-t">
            <button type="submit"
                class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Update
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

        $("#form-edit").validate({
            rules: {
                nama_alat: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                merk: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                tahun_pengadaan: {
                    required: true,
                    number: true,
                    maxlength: 4
                },
                lokasi: {
                    required: true
                },
                status_kondisi {
                    required: true
                },
                foto_alat: {
                    required: false,
                    extension: "jpg|jpeg|png",
                    filesize: 2097152
                }

            },
            messages: {
                nama_alat: {
                    required: "Nama alat wajib diisi.",
                    minlength: "Minimal 3 karakter.",
                    maxlength: "Maksimal 100 karakter."
                },
                merk: {
                    required: "Merk wajib diisi.",
                    minlength: "Minimal 3 karakter.",
                    maxlength: "Maksimal 100 karakter."
                },
                tahun_pengadaan: {
                    required: "Tahun pengadaan wajib diisi.",
                    number: "Harus berupa angka.",
                    maxlength: "Maksimal 4 digit."
                },
                lokasi: {
                    required: "Silahkan pilih laboratorium."
                },
                foto_alat: {
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
