<form action="{{ url('peminjaman/' . $peminjaman->id . '/prosesPengembalian') }}" method="POST" id="form-return"
    class="w-full validate" enctype="multipart/form-data">
    @csrf
    <div class="relative w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg flex flex-col max-h-[90vh]">

        <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50 rounded-t-lg shrink-0">
            <h5 class="text-lg font-semibold text-gray-800">Proses Pengembalian</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="hideModal()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <div class="px-6 py-4 space-y-5 overflow-y-auto flex-1 custom-scrollbar">

            <div class="bg-blue-50 border border-blue-100 rounded-md p-4 text-sm text-gray-700">
                <p>Memproses pengembalian dari: <span id="returnPeminjamName"
                        class="font-semibold text-gray-900">{{ $peminjaman->peminjam->nama }}</span></p>
                <p class="mt-1">Tenggat Waktu: <span id="returnDeadline"
                        class="font-mono font-medium text-red-600">{{ $peminjaman->end_time->translatedFormat('d F Y H:i') }}</span>
                </p>
            </div>

            <div>
                <label for="bukti_pengembalian" class="block text-sm font-medium text-gray-700">
                    Upload Bukti Pengembalian <span class="text-red-500">*</span>
                </label>
                <div class="mt-1 flex items-center">
                    <input type="file" name="bukti_pengembalian" id="bukti_pengembalian" accept="image/*" required
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                </div>
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maks: 2MB.</p>
                <p id="error-bukti_pengembalian" class="mt-1 text-sm text-red-600 error-text"></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="realisasiDate" class="block text-sm font-medium text-gray-700">
                        Tanggal Pengembalian <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="tanggal_kembali_realisasi" id="realisasiDate"
                        data-deadline="{{ $peminjaman->end_time->format('Y-m-d H:i:s') }}"
                        data-start-time="{{ $peminjaman->start_time->format('Y-m-d H:i:s') }}"
                        class="bg-white mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                        required>
                    <p id="error-tanggal_kembali_realisasi" class="mt-1 text-sm text-red-600 error-text"></p>
                </div>

                <div>
                    <label for="status_pengembalian" class="block text-sm font-medium text-gray-700">
                        Status Pengembalian
                    </label>
                    <select name="status_pengembalian" id="status_pengembalian"
                        class="mt-1 block w-full rounded-md bg-gray-100 border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 pointer-events-none"
                        readonly>
                        <option value="tepat_waktu">Tepat Waktu</option>
                        <option value="terlambat">Terlambat</option>
                    </select>
                </div>
            </div>

            <div id="warning-terlambat"
                class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-md flex items-start">
                <svg class="h-5 w-5 text-red-600 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <div>
                    <p class="text-sm font-bold text-red-700">Terlambat!</p>
                    <p class="text-xs text-red-600">Tanggal pengembalian melewati tenggat waktu.</p>
                </div>
            </div>

            @if ($detailAlat->isNotEmpty())
                <div>
                    <h6 class="text-sm font-bold text-gray-800 border-b pb-2 mb-3">Pengecekan Kondisi Alat</h6>
                    <div class="space-y-3">
                        @foreach ($detailAlat as $detail)
                            <div
                                class="flex flex-col md:flex-row md:items-center justify-between gap-2 p-3 bg-gray-50 border border-gray-200 rounded-md">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $detail->alat->nama_alat }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-gray-500">Jml: {{ $detail->jumlah }}</span>
                                        <span
                                            class="text-xs px-2 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-200">
                                            Kondisi Pinjam: {{ $detail->kondisi_saat_pinjam ?? 'Tidak ada catatan' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full md:w-1/2">
                                    <input type="hidden" name="detail_id[]" value="{{ $detail->id }}">
                                    <select name="kondisi_saat_kembali[]" required
                                        class="mt-1 block w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 kondisi-kembali-select">
                                        <option value="">-- Cek Kondisi --</option>
                                        <option value="Sesuai Kondisi Awal">Sesuai Kondisi Awal</option>
                                        <option value="Rusak Baru">Rusak Baru</option>
                                        <option value="Hilang Sebagian/Total">Hilang Sebagian/Total</option>
                                    </select>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p id="error-kondisi_saat_kembali" class="mt-2 text-sm text-red-600 error-text"></p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-4">
                <div>
                    <label for="denda_atau_sanksi" class="block text-sm font-medium text-gray-700">Denda / Sanksi
                        (Opsional)</label>
                    <input type="text" name="denda_atau_sanksi" id="denda_atau_sanksi" placeholder="Misal: Rp 50.000"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                    <textarea name="catatan" id="catatan" rows="2"
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200"></textarea>
                </div>
            </div>

        </div>

        <div class="flex justify-end gap-2 px-6 py-4 border-t bg-gray-50 rounded-b-lg shrink-0">
            <button type="button"
                class="rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                onclick="hideModal()">
                Batal
            </button>
            <button type="submit"
                class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Konfirmasi Selesai
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

        $.validator.addMethod("checkSemuaKondisiKembali", function(value, element) {
            let valid = true;
            $(".kondisi-kembali-select").each(function() {
                if ($(this).val() === "") {
                    valid = false;
                }
            });
            return valid;
        }, "Harap pilih kondisi kembali untuk semua alat.");

        $("#form-return").validate({
            onfocusout: false,
            onkeyup: false,
            onclick: false,
            ignore: [],
            errorElement: "span",
            errorClass: "text-red-500 text-xs mt-1 block",
            rules: {
                bukti_pengembalian: {
                    required: true,
                    extension: "jpg|jpeg|png",
                    filesize: 2097152
                },
                tanggal_kembali_realisasi: {
                    required: true
                },
                "kondisi_saat_kembali[]": {
                    checkSemuaKondisiKembali: true
                }
            },
            messages: {
                bukti_pengembalian: {
                    required: "Bukti foto pengembalian wajib diunggah.",
                    extension: "Hanya file gambar (JPG, JPEG, PNG).",
                    filesize: "Ukuran file terlalu besar (maksimal 2MB)."
                },
                tanggal_kembali_realisasi: {
                    required: "Tanggal realisasi pengembalian wajib diisi."
                }
            },
            errorPlacement: function(error, element) {
                let name = element.attr("name");
                if (name === "bukti_pengembalian") {
                    $("#error-bukti_pengembalian").html(error);
                } else if (name === "tanggal_kembali_realisasi") {
                    $("#error-tanggal_kembali_realisasi").html(error);
                } else if (element.hasClass("kondisi-kembali-select")) {
                    $("#error-kondisi_saat_kembali").html(error);
                } else {
                    error.insertAfter(element);
                }
            }
        });

        function checkDeadline(selectedDates, instance) {
            let deadlineStr = instance.element.getAttribute('data-deadline');
            let deadlineDate = new Date(deadlineStr);
            let selectedDate = selectedDates[0];

            let warningBox = document.getElementById('warning-terlambat');
            let statusSelect = document.getElementById('status_pengembalian');

            if (selectedDate > deadlineDate) {
                warningBox.classList.remove('hidden');
                if (statusSelect) statusSelect.value = 'terlambat';
            } else {
                warningBox.classList.add('hidden');
                if (statusSelect) statusSelect.value = 'tepat_waktu';
            }
        }

        const inputElement = document.getElementById('realisasiDate');
        const startTimeStr = inputElement.getAttribute('data-start-time');

        flatpickr("#realisasiDate", {
            enableTime: true,
            dateFormat: "Y-m-d H:i:s",
            altInput: true,
            altFormat: "l, d F Y H:i",
            time_24hr: true,
            locale: "id",
            defaultDate: new Date(),
            minDate: startTimeStr,
            maxDate: new Date(),
            allowInput: true,
            onChange: function(selectedDates, dateStr, instance) {
                checkDeadline(selectedDates, instance);
            },
            onReady: function(selectedDates, dateStr, instance) {
                if (selectedDates.length > 0) {
                    checkDeadline(selectedDates, instance);
                }
            }
        });
    });
</script>
