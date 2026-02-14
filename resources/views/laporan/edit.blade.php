<form action="{{ url('laporan/' . $laporan->id . '/update') }}" method="POST" id="form-edit" class="w-full validate">
    @csrf

    <div class="relative w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h5 class="text-lg font-semibold text-gray-800">Tindak Lanjut Perbaikan</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="hideModal()">✕</button>
        </div>

        <div class="px-6 py-4 space-y-4">
            <div>
                <div class="block text-sm font-medium text-gray-700">Nama Alat</div>
                <div
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed">
                    {{ $laporan->alat->nama_alat }}</div>
            </div>

            <div>
                <div class="block text-sm font-medium text-gray-700">Deskripsi
                    Kerusakan</div>
                <textarea name="deskripsi_kerusakan" id="deskripsi_kerusakan" rows="3"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed"
                    readonly>{{ $laporan->deskripsi_kerusakan }}</textarea>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status Tindak Lanjut</label>
                <select name="status" id="status"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-white focus:border-blue-500 focus:ring focus:ring-blue-200">
                    <option value="menunggu" {{ $laporan->status_tindak_lanjut == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="sedang_diperbaiki" {{ $laporan->status_tindak_lanjut == 'sedang_diperbaiki' ? 'selected' : '' }}>
                        Sedang Diperbaiki</option>
                    <option value="selesai" {{ $laporan->status_tindak_lanjut == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="afkir" {{ $laporan->status_tindak_lanjut == 'afkir' ? 'selected' : '' }}>Afkir</option>
                </select>
                <p id="error-status" class="mt-1 text-sm text-red-600"></p>
            </div>

            <div>
                <label for="keterangan_perbaikan" class="block text-sm font-medium text-gray-700">Keterangan
                    Perbaikan <span id="required-mark" class="text-red-500 hidden">* (Wajib diisi jika
                        selesai)</span></label>
                <textarea name="keterangan_perbaikan" id="keterangan_perbaikan" rows="3"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 bg-white focus:border-blue-500 focus:ring focus:ring-blue-200">{{ $laporan->keterangan_perbaikan }}</textarea>
                <p id="error-keterangan_perbaikan" class="mt-1 text-sm text-red-600"></p>
            </div>

            <div class="flex justify-start gap-2 pt-4 border-t">
                <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Simpan
                    Perubahan</button>
                <button type="button" class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600"
                    onclick="hideModal()">Batal</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#status').on('change', function() {
            if ($(this).val() === 'selesai') {
                $('#required-mark').removeClass('hidden');
            } else {
                $('#required-mark').addClass('hidden');
                $("#form-edit").validate().element("#keterangan_perbaikan");
            }
        });

        $("#form-edit").validate({
            rules: {
                status: {
                    required: true
                },
                keterangan_perbaikan: {
                    required: function(element) {
                        return $("#status").val() === "selesai";
                    }
                }
            },
            messages: {
                status: {
                    required: "Status wajib diisi."
                },
                keterangan_perbaikan: {
                    required: "Keterangan perbaikan wajib diisi jika status laporan selesai."
                },
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
