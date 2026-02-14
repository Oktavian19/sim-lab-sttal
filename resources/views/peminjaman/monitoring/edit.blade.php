<form action="{{ url('peminjaman/' . $peminjaman->id . '/kembali') }}" method="POST" id="form-return" class="w-full validate">
    @csrf
    <input type="hidden" name="peminjaman_id" id="returnPeminjamanId">

    <div class="relative w-full max-w-2xl mx-auto bg-white rounded-lg shadow-lg">

        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h5 class="text-lg font-semibold text-gray-800">Proses Pengembalian</h5>
            <button type="button" class="text-gray-400 hover:text-gray-600" onclick="hideModal()">
                ✕
            </button>
        </div>

        <div class="px-6 py-4 space-y-4">

            <div class="bg-blue-50 border border-blue-100 rounded-md p-4 text-sm text-gray-700">
                <p>Memproses pengembalian dari: <span id="returnPeminjamName"
                        class="font-semibold text-gray-900">{{ $peminjaman->peminjam->nama }}</span></p>
                <p class="mt-1">Tenggat Waktu: <span id="returnDeadline"
                        class="font-mono font-medium text-red-600">{{ $peminjaman->end_time->translatedFormat('d F Y H:i') }}</span>
                </p>
            </div>

            <div>
                <label for="realisasiDate" class="block text-sm font-medium text-gray-700">
                    Tanggal Pengembalian
                </label>

                <input type="text" name="tanggal_kembali_realisasi" id="realisasiDate"
                    data-deadline="{{ $peminjaman->end_time->format('Y-m-d H:i:s') }}"
                    data-start-time="{{ $peminjaman->start_time->format('Y-m-d H:i:s') }}"
                    class="bg-white mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
               focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required>

                <p id="error-tanggal_kembali_realisasi" class="mt-1 text-sm text-red-600"></p>

                <div id="warning-terlambat"
                    class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-md flex items-start">
                    <svg class="h-5 w-5 text-red-600 mt-0.5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-red-700">Terlambat!</p>
                        <p class="text-xs text-red-600">Tanggal pengembalian melewati tenggat waktu.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="status_pengembalian" class="block text-sm font-medium text-gray-700">
                        Status Pengembalian
                    </label>
                    <select name="status_pengembalian" id="status_pengembalian"
                        class="mt-1 block w-full rounded-md bg-white border border-gray-300 px-3 py-2
                               focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="tepat_waktu">Tepat Waktu</option>
                        <option value="terlambat">Terlambat</option>
                    </select>
                </div>
                <div>
                    <label for="kondisi_saat_kembali" class="block text-sm font-medium text-gray-700">
                        Kondisi Barang
                    </label>
                    <select name="kondisi_saat_kembali" id="kondisi_saat_kembali"
                        class="mt-1 block w-full rounded-md bg-white border border-gray-300 px-3 py-2
                               focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="baik">Lengkap & Baik</option>
                        <option value="rusak">Ada Kerusakan</option>
                        <option value="hilang">Ada yang Hilang</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="denda_atau_sanksi" class="block text-sm font-medium text-gray-700">
                    Denda / Sanksi (Opsional)
                </label>
                <input type="text" name="denda_atau_sanksi" id="denda_atau_sanksi"
                    placeholder="Contoh: Denda Rp 50.000 atau Teguran Lisan"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <div>
                <label for="catatan" class="block text-sm font-medium text-gray-700">
                    Catatan Tambahan
                </label>
                <textarea name="catatan" id="catatan" rows="2"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
            </div>
        </div>

        <div class="flex justify-start gap-2 px-6 py-4 border-t">
            <button type="submit"
                class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Konfirmasi Selesai
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
        $("#form-return").validate({
            rules: {
                tanggal_kembali_realisasi: {
                    required: true
                },
                status_pengembalian: {
                    required: true
                },
                kondisi_saat_kembali: {
                    required: true
                }
            },
            messages: {
                tanggal_kembali_realisasi: {
                    required: "Tanggal pengembalian wajib diisi."
                },
                status_pengembalian: {
                    required: "Status wajib dipilih."
                },
                kondisi_saat_kembali: {
                    required: "Kondisi barang wajib dipilih."
                }
            },
            errorPlacement: function(error, element) {
                let name = element.attr("name");
                $("#error-" + name).html(error);
            },
            highlight: function(element) {
                $(element).addClass("border-red-500 ring-red-200").removeClass("border-gray-300");
            },
            unhighlight: function(element) {
                $(element).removeClass("border-red-500 ring-red-200").addClass("border-gray-300");
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
