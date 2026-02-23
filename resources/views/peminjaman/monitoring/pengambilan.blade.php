<form action="{{ url('peminjaman/' . $peminjaman->id . '/prosesPengambilan') }}" method="POST" id="form-pengambilan"
    class="w-full validate" enctype="multipart/form-data">
    @csrf
    <div
        class="relative w-full max-w-2xl mx-auto bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">

        <!-- Header Modal -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h5 class="text-lg font-bold text-gray-900 flex items-center gap-2"> Proses Pengambilan Peminjaman
            </h5>
            <button type="button" class="p-2 rounded-full hover:bg-gray-200 transition-colors focus:outline-none"
                onclick="hideModal()">
                <i class="fa-solid fa-xmark text-gray-500 text-lg flex items-center justify-center w-5 h-5"></i>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="px-6 py-5 space-y-6 overflow-y-auto">

            <!-- Info Peminjam -->
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-slate-700 shadow-sm">
                <p>Penyerahan fasilitas kepada: <span
                        class="font-bold text-slate-900">{{ $peminjaman->peminjam->nama }}</span></p>
                <p class="mt-1">Kegiatan: <span class="font-medium">{{ $peminjaman->kegiatan }}</span></p>
            </div>

            <!-- Upload Bukti -->
            <div>
                <label for="bukti_pengambilan" class="block text-sm font-bold text-slate-700 mb-2">
                    Upload Bukti Pengambilan <span class="text-red-500">*</span>
                </label>
                <div class="mt-1">
                    <input type="file" name="bukti_pengambilan" id="bukti_pengambilan" accept="image/*" required
                        class="block w-full text-sm text-slate-500 
                        file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 
                        file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 
                        hover:file:bg-blue-100 border border-slate-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all cursor-pointer">
                </div>
                <p class="mt-1.5 text-xs text-slate-500 flex items-center gap-1">
                    <i class="fa-solid fa-circle-info"></i> Format: JPG, JPEG, PNG. Maks: 2MB.
                </p>
                <p id="error-bukti_pengambilan" class="mt-1 text-sm text-red-600 error-text"></p>
            </div>

            <!-- List Kondisi Alat -->
            @if ($detailAlat->isNotEmpty())
                <div>
                    <h6
                        class="text-sm font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-clipboard-check text-slate-500"></i> Kondisi Alat Saat Dipinjam
                    </h6>

                    <div class="space-y-3">
                        @foreach ($detailAlat as $detail)
                            <div
                                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-white border border-slate-200 rounded-xl hover:border-blue-300 transition-colors shadow-sm group">
                                <div class="flex-1">
                                    <p
                                        class="text-sm font-bold text-slate-900 group-hover:text-blue-700 transition-colors">
                                        {{ $detail->alat->nama_alat }}</p>
                                    <p class="text-xs text-slate-500 mt-1 flex flex-wrap gap-2">
                                        <span
                                            class="inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded text-slate-600">
                                            <i class="fa-solid fa-layer-group text-[10px]"></i> Qty:
                                            {{ $detail->jumlah }}
                                        </span>
                                        <span
                                            class="inline-flex items-center gap-1 bg-slate-100 px-2 py-0.5 rounded text-slate-600">
                                            <i class="fa-solid fa-location-dot text-[10px]"></i>
                                            {{ $detail->alat->laboratorium->nama_lab }}
                                        </span>
                                    </p>
                                </div>
                                <div class="w-full sm:w-1/3 shrink-0">
                                    <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">

                                    <select name="kondisi_saat_pinjam[]" required
                                        class="block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none transition-all kondisi-select">
                                        <option value="">-- Pilih Kondisi --</option>
                                        <option value="baik">Baik & Lengkap</option>
                                        <option value="rusak_ringan">Rusak Ringan</option>
                                        <option value="tidak_lengkap">Tidak Lengkap</option>
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p id="error-kondisi_saat_pinjam" class="mt-2 text-sm text-red-600 error-text"></p>
                </div>
            @endif

        </div>

        <!-- Footer Modal -->
        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 px-6 py-4 border-t border-gray-200 bg-gray-50">
            <button type="button"
                class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-gray-50 focus:outline-none transition-colors"
                onclick="hideModal()">
                Batal
            </button>
            <button type="submit"
                class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none shadow-sm transition-colors">
                <i class="fa-solid fa-check-double"></i> Konfirmasi Diambil
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

        $.validator.addMethod("checkSemuaKondisi", function(value, element) {
            let valid = true;
            $(".kondisi-select").each(function() {
                if ($(this).val() === "") {
                    valid = false;
                }
            });
            return valid;
        }, "Harap pilih kondisi untuk semua alat.");

        $("#form-pengambilan").validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            ignore: [],
            errorElement: "span",
            errorClass: "text-red-500 text-xs font-medium mt-1.5 block",
            rules: {
                bukti_pengambilan: {
                    required: true,
                    extension: "jpg|jpeg|png",
                    filesize: 2097152
                },
                "kondisi_saat_pinjam[]": {
                    checkSemuaKondisi: true
                }
            },
            messages: {
                bukti_pengambilan: {
                    required: "Bukti foto pengambilan wajib diunggah.",
                    extension: "Hanya menerima file gambar (JPG, JPEG, PNG).",
                    filesize: "Ukuran file terlalu besar (maksimal 2MB)."
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") === "bukti_pengambilan") {
                    $("#error-bukti_pengambilan").html(error);
                } else if (element.hasClass("kondisi-select")) {
                    $("#error-kondisi_saat_pinjam").html(error);
                } else {
                    error.insertAfter(element);
                }
            }
        });

        $(".kondisi-select").on('change', function() {
            let semuaTerisi = true;
            $(".kondisi-select").each(function() {
                if ($(this).val() === "") semuaTerisi = false;
            });

            if (semuaTerisi) {
                $("#error-kondisi_saat_pinjam").empty();
            }
        });
    });
</script>
