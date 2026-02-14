<form action="{{ url('laboratorium/store') }}" method="POST" id="form-tambah" class="w-full validate">
    @csrf

    <div class="relative w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h5 class="text-lg font-semibold text-gray-800">Tambah Laboratorium</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="hideModal()">
                ✕
            </button>

        </div>

        <div class="px-6 py-4 space-y-4">
            <div>
                <label for="nama_lab" class="block text-sm font-medium text-gray-700">
                    Nama Laboratorium
                </label>
                <input type="text" name="nama_lab" id="nama_lab"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
                <p id="error-nama_lab" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label for="kapasitas" class="block text-sm font-medium text-gray-700">
                    Kapasitas
                </label>
                <input type="number" name="kapasitas" id="kapasitas"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>
                <p id="error-kapasitas" class="mt-1 text-sm text-red-600"></p>
            </div>
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                    Deskripsi
                </label>
                <textarea name="deskripsi" id="deskripsi" rows="3"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                <p id="error-deskripsi" class="mt-1 text-sm text-red-600"></p>
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
        $("#form-tambah").validate({
            rules: {
                nama_lab: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                kapasitas: {
                    required: true,
                    min: 1,
                }

            },
            messages: {
                nama_lab: {
                    required: "Nama laboratorium wajib diisi.",
                    minlength: "Minimal 3 karakter.",
                    maxlength: "Maksimal 100 karakter."
                },
                kapasitas: {
                    required: "Kapasitas wajib diisi.",
                    min: "Kapasitas minimal 1 orang."
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
