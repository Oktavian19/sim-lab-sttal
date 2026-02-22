@extends('layouts.app')

@section('title', 'Formulir Peminjaman - SIM LAB STTAL Admin')
@section('header', 'Formulir Peminjaman')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">
        <div class="mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Formulir Peminjaman</h2>
                <p class="text-sm text-gray-500 mt-1">Isi detail kegiatan untuk meminjam fasilitas laboratorium atau alat.
                </p>
            </div>

            <form id="loanForm" action="{{ route('peminjaman.store') }}" method="POST" class="p-6 space-y-5 validate">
                @csrf

                <section class="space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                        <div
                            class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                            1</div>
                        Detail Kegiatan
                    </h3>

                    <div
                        class="mb-2 p-4 bg-blue-50 border border-blue-100 rounded-lg flex items-center gap-3 transition-colors hover:bg-blue-100/50">
                        <input type="checkbox" id="hanya_alat" name="hanya_alat" value="1"
                            class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
                        <div>
                            <label for="hanya_alat"
                                class="font-semibold text-blue-900 text-sm cursor-pointer select-none">Hanya Pinjam
                                Alat</label>
                            <p class="text-xs text-blue-700 select-none">Centang jika Anda hanya meminjam alat dan tidak
                                menggunakan ruangan laboratorium.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="kegiatan" required placeholder="Contoh: Praktikum Basis Data Lanjut"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                            <p id="error-kegiatan" class="mt-1 text-sm text-red-600"></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:col-span-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Peminjaman <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="tanggal_peminjaman" id="tanggal_peminjaman"
                                    class="bg-white w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm"
                                    placeholder="Pilih tanggal...">
                                <p id="error-tanggal_peminjaman" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="start_time" id="start_time"
                                    class="bg-white w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm"
                                    placeholder="Pilih jam mulai...">
                                <p id="error-start_time" class="mt-1 text-sm text-red-600"></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="end_time" id="end_time"
                                    class="bg-white w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm"
                                    placeholder="Pilih jam selesai...">
                                <p id="error-end_time" class="mt-1 text-sm text-red-600"></p>
                            </div>
                        </div>

                        <div id="peserta_wrapper" class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi Peserta <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_peserta" id="jumlah_peserta" min="1"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm"
                                placeholder="Contoh: 30">
                            <p id="error-jumlah_peserta" class="mt-1 text-sm text-red-600"></p>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100" />

                <section class="space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                        <div
                            class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                            2</div>
                        <span id="label-lab">Lokasi (Laboratorium)</span>
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($labs as $lab)
                            <label class="cursor-pointer group">
                                <input type="radio" name="id_lab" value="{{ $lab->id }}"
                                    class="peer sr-only lab-radio">
                                <div
                                    class="relative flex flex-col p-4 border border-gray-200 rounded-xl hover:border-blue-300 transition-all bg-white h-full peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-100">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-semibold text-gray-900 text-sm">{{ $lab->nama_lab }}</span>
                                        <span
                                            class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold uppercase">{{ ucfirst($lab->status) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $lab->deskripsi }}</p>
                                    <div class="mt-auto flex items-center gap-2 text-xs text-gray-600">
                                        <i class="fa-solid fa-users text-blue-400"></i> Kapasitas: {{ $lab->kapasitas }}
                                    </div>
                                    <div
                                        class="absolute top-2 right-2 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity hidden">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <p id="error-id_lab" class="mt-1 text-sm text-red-600"></p>
                </section>

                <hr class="border-gray-100" />

                <section class="space-y-4">
                    <div class="flex justify-between items-center flex-wrap gap-2">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                            <div
                                class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                3</div>
                            <span id="label-alat">Peminjaman Alat (Opsional)</span>
                        </h3>
                        <span
                            class="text-xs font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100"
                            id="tool-count-badge">
                            0 alat dipilih
                        </span>
                    </div>

                    <div class="relative">
                        <i
                            class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="toolSearch"
                            placeholder="Pilih lab terlebih dahulu, lalu cari nama alat..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 transition">
                    </div>

                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg divide-y divide-gray-100 bg-white"
                        id="toolsContainer">
                        <div id="no-lab-msg" class="p-4 text-center text-sm text-gray-500">Silakan pilih lokasi
                            (Laboratorium) untuk menampilkan alat yang tersedia.</div>

                        @foreach ($alats as $alat)
                            <div class="tool-item flex flex-col p-3 hover:bg-gray-50 transition group border-b border-gray-50 hidden"
                                data-alat-id="{{ $alat->id }}"
                                data-lab-id="{{ $alat->lokasi ?? $alat->laboratorium_id }}">
                                <div class="flex items-center justify-between cursor-pointer w-full"
                                    onclick="toggleTool(this.parentElement, '{{ $alat->id }}')">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="checkbox-indicator w-5 h-5 rounded border border-gray-300 flex items-center justify-center transition bg-white text-white group-hover:border-blue-400">
                                            <i class="fa-solid fa-check text-[10px]"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800 tool-name">{{ $alat->nama_alat }}
                                            </p>
                                            <p class="text-[11px] text-gray-500">
                                                {{ $alat->merk }} • Lokasi: {{ $alat->laboratorium->nama_lab }}
                                                <span
                                                    class="stock-label ml-1 px-1.5 py-0.5 bg-gray-100 rounded text-gray-700 font-bold"
                                                    data-original-stock="{{ $alat->jumlah }}">Stok:
                                                    {{ $alat->jumlah }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <input type="checkbox" name="alat_ids[]" value="{{ $alat->id }}"
                                        class="hidden tool-checkbox">
                                </div>

                                <div class="quantity-container hidden mt-3 pl-8 pb-1 transition-all duration-300">
                                    <div class="flex items-center gap-3">
                                        <label class="text-xs font-medium text-gray-600">Jumlah Pinjam:</label>
                                        <input type="number" name="jumlah_alat[{{ $alat->id }}]" min="1"
                                            max="{{ $alat->jumlah }}" value="1"
                                            class="w-20 px-2 py-1 text-sm border border-gray-300 rounded-md focus:ring-1 focus:ring-blue-500 outline-none quantity-input"
                                            oninput="validateQuantity(this)">
                                        <span class="text-[10px] text-red-500 italic hidden error-qty">Maksimal <span
                                                class="max-qty-text">{{ $alat->jumlah }}</span></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p id="error-alat_ids" class="mt-1 text-sm text-red-600 font-medium"></p>
                </section>

                <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium text-sm transition">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm font-medium text-sm flex items-center gap-2 transition">
                        <i class="fa-solid fa-paper-plane"></i> Ajukan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        function toggleTool(itemElement, id) {
            const checkbox = itemElement.querySelector('.tool-checkbox');
            const indicator = itemElement.querySelector('.checkbox-indicator');
            const qtyContainer = itemElement.querySelector('.quantity-container');
            const qtyInput = itemElement.querySelector('.quantity-input');

            if (itemElement.classList.contains('cursor-not-allowed')) return;

            checkbox.checked = !checkbox.checked;

            if (checkbox.checked) {
                itemElement.classList.add('bg-blue-50');
                indicator.classList.remove('bg-white', 'border-gray-300');
                indicator.classList.add('bg-blue-600', 'border-blue-600');
                qtyContainer.classList.remove('hidden');
                qtyInput.setAttribute('required', 'required');
            } else {
                itemElement.classList.remove('bg-blue-50');
                indicator.classList.add('bg-white', 'border-gray-300');
                indicator.classList.remove('bg-blue-600', 'border-blue-600');
                qtyContainer.classList.add('hidden');
                qtyInput.removeAttribute('required');
                qtyInput.value = 1;
                itemElement.querySelector('.error-qty').classList.add('hidden');
            }

            updateToolCount();
        }

        function validateQuantity(input) {
            let value = parseInt(input.value);
            let maxStock = parseInt(input.getAttribute('max'));
            const errorText = input.nextElementSibling;

            if (value > maxStock) {
                input.value = maxStock;
                errorText.classList.remove('hidden');
                setTimeout(() => errorText.classList.add('hidden'), 2000);
            } else if (value < 1 || isNaN(value)) {
                input.value = '';
            } else {
                errorText.classList.add('hidden');
            }
        }

        function checkLiveStock() {
            let tanggal = $('#tanggal_peminjaman').val(); 
            let start = $('#start_time').val(); 
            let end = $('#end_time').val(); 

            if (!tanggal || !start || !end) return;

            let startDateTime = `${tanggal} ${start}:00`;
            let endDateTime = `${tanggal} ${end}:00`;

            let payload = {
                start_time: startDateTime,
                end_time: endDateTime
            };

            $('#toolsContainer').css('opacity', '0.5');

            $.ajax({
                url: "{{ route('peminjaman.check-stock') }}",
                type: "GET",
                data: payload,
                success: function(res) {
                    if (res.status) {
                        let stocks = res.data;
                        $('.tool-item').each(function() {
                            let item = $(this);
                            let alatId = item.attr('data-alat-id');
                            let stockLabel = item.find('.stock-label');
                            let qtyInput = item.find('.quantity-input');
                            let checkbox = item.find('.tool-checkbox');

                            let currentStock = stocks[alatId] !== undefined ? stocks[alatId] : parseInt(stockLabel.data('original-stock'));

                            stockLabel.text('Stok: ' + currentStock);
                            qtyInput.attr('max', currentStock);
                            item.find('.max-qty-text').text(currentStock);

                            if (currentStock <= 0) {
                                item.addClass('opacity-50 pointer-events-none cursor-not-allowed');
                                checkbox.prop('checked', false);
                                item.removeClass('bg-blue-50');
                                item.find('.checkbox-indicator').removeClass(
                                    'bg-blue-600 border-blue-600').addClass(
                                    'bg-white border-gray-300');
                                item.find('.quantity-container').addClass('hidden');
                                qtyInput.removeAttr('required');
                            } else {
                                item.removeClass('opacity-50 pointer-events-none cursor-not-allowed');
                                if (checkbox.is(':checked')) {
                                    if (parseInt(qtyInput.val()) > currentStock) {
                                        qtyInput.val(currentStock);
                                        item.find('.error-qty').removeClass('hidden');
                                        setTimeout(() => item.find('.error-qty').addClass('hidden'),
                                            3000);
                                    }
                                }
                            }
                        });
                        updateToolCount();
                    }
                },
                complete: function() {
                    $('#toolsContainer').css('opacity', '1');
                }
            });
        }

        function updateToolCount() {
            const checkedCount = document.querySelectorAll('.tool-checkbox:checked').length;
            const badge = document.getElementById('tool-count-badge');
            badge.innerText = `${checkedCount} alat dipilih`;
        }

        $(document).ready(function() {
            $('#hanya_alat').on('change', function() {
                let isHanyaAlat = $(this).is(':checked');

                if (isHanyaAlat) {
                    $('#peserta_wrapper').slideUp();
                    $('#jumlah_peserta').val('').prop('required', false);
                    $('#label-lab').html(
                        'Lokasi Alat (Pilih lab untuk menemukan alat)'
                    );
                    $('#label-alat').html('Peminjaman Alat <span class="text-red-500">*</span>');
                } else {
                    $('#peserta_wrapper').slideDown();
                    $('#jumlah_peserta').prop('required', true);
                    $('#label-lab').html('Lokasi (Laboratorium)');
                    $('#label-alat').html('Peminjaman Alat (Opsional)');
                }

                checkLiveStock();
            });

            $('.lab-radio').on('change', function() {
                let selectedLab = $(this).val();
                $('#no-lab-msg').addClass('hidden');

                $('.tool-item').each(function() {
                    let toolLabId = $(this).data('lab-id');
                    if (toolLabId == selectedLab) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                        let checkbox = $(this).find('.tool-checkbox');
                        if (checkbox.is(':checked')) {
                            toggleTool(this, $(this).data('alat-id'));
                        }
                    }
                });
                $('#toolSearch').trigger('keyup');
            });

            $('#toolSearch').on('keyup', function(e) {
                const filter = e.target.value.toLowerCase();
                const selectedLabId = $('input[name="id_lab"]:checked').val();

                $('.tool-item').each(function() {
                    const name = $(this).find('.tool-name').text().toLowerCase();
                    const toolLabId = $(this).attr('data-lab-id');

                    if (toolLabId == selectedLabId && name.includes(filter)) {
                        $(this).css("display", "flex");
                    } else {
                        $(this).css("display", "none");
                    }
                });
            });

            const now = new Date();

            const datePicker = flatpickr("#tanggal_peminjaman", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "l, d F Y",
                locale: "id",
                minDate: "today",
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates.length > 0) {
                        let selectedDate = selectedDates[0];
                        let isToday = selectedDate.toDateString() === now.toDateString();

                        if (isToday) {
                            let currentHour = ("0" + now.getHours()).slice(-2);
                            let currentMin = ("0" + now.getMinutes()).slice(-2);
                            let minTimeStr = currentHour + ":" + currentMin;

                            timeStartPicker.set('minTime', minTimeStr);

                            let selectedStartTime = $('#start_time').val();
                            if (selectedStartTime && selectedStartTime < minTimeStr) {
                                timeStartPicker.setDate(null, false);
                                timeEndPicker.setDate(null, false);

                                $('#start_time, #end_time').removeClass('border-red-500 ring-red-200')
                                    .addClass('border-gray-300');
                                $('#error-start_time, #error-end_time').empty();
                            }
                        } else {
                            timeStartPicker.set('minTime', null);
                        }
                    }

                    $("#tanggal_peminjaman").valid();
                    checkLiveStock();
                }
            });

            const timeStartPicker = flatpickr("#start_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                onChange: function(selectedDates, timeStr) {
                    timeEndPicker.set('minTime', timeStr);
                    $("#start_time").valid();
                    checkLiveStock();
                }
            });

            const timeEndPicker = flatpickr("#end_time", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                onChange: function(selectedDates, timeStr) {
                    $("#end_time").valid();
                    checkLiveStock();
                }
            });

            $.validator.addMethod("greaterThanTime", function(value, element, param) {
                var startTime = $(param).val();
                if (!value || !startTime) return true;

                return value > startTime;
            }, "Waktu selesai harus setelah waktu mulai.");

            $("#loanForm").validate({
                ignore: "",
                rules: {
                    kegiatan: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    id_lab: {
                        required: true
                    },
                    tanggal_peminjaman: {
                        required: true
                    },
                    start_time: {
                        required: true
                    },
                    end_time: {
                        required: true,
                        greaterThanTime: "#start_time"
                    },
                    jumlah_peserta: {
                        required: function() {
                            return !$("#hanya_alat").is(':checked');
                        },
                        min: 1
                    },
                    "alat_ids[]": {
                        required: function() {
                            return $('#hanya_alat').is(':checked');
                        }
                    }
                },
                messages: {
                    kegiatan: {
                        required: 'Nama Kegiatan wajib diisi.'
                    },
                    id_lab: {
                        required: 'Silakan pilih lokasi lab.'
                    },
                    tanggal_peminjaman: {
                        required: 'Pilih tanggal peminjaman.'
                    },
                    start_time: {
                        required: 'Pilih jam mulai.'
                    },
                    end_time: {
                        required: 'Pilih jam selesai.'
                    },
                    jumlah_peserta: {
                        required: 'Jumlah peserta wajib diisi.'
                    },
                    "alat_ids[]": {
                        required: 'Silakan pilih minimal 1 alat untuk dipinjam.'
                    }
                },
                errorPlacement: function(error, element) {
                    let name = element.attr("name");
                    if (name === "alat_ids[]") {
                        $("#error-alat_ids").html(error);
                    } else {
                        $("#error-" + name).html(error);
                    }
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

        $(document).on('submit', 'form.validate', function(e) {
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
                success: function(res) {
                    if (res.status) {
                        hideModal();

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        document.getElementById('loanForm').reset();
                        document.querySelectorAll('#toolsContainer .tool-checkbox').forEach(cb => cb
                            .checked = false);

                        document.querySelectorAll('#toolsContainer .tool-item').forEach(item => {
                            item.classList.remove('bg-blue-50', 'border-blue-200');

                            const indicator = item.querySelector('.checkbox-indicator');
                            if (indicator) {
                                indicator.classList.remove('bg-blue-600', 'border-blue-600');
                                indicator.classList.add('bg-white', 'border-gray-300');
                            }

                            const qtyContainer = item.querySelector('.quantity-container');
                            if (qtyContainer) {
                                qtyContainer.classList.add('hidden');
                            }
                            const qtyInput = item.querySelector('.quantity-input');
                            if (qtyInput) {
                                qtyInput.value = 1;
                                qtyInput.removeAttribute('required');
                            }
                        });

                        updateToolCount();
                    } else {
                        let errorListHtml = '';

                        if (res.errors && Array.isArray(res.errors)) {
                            errorListHtml = res.errors.map(err => `
                            <div style="
                                background-color: #fef2f2; 
                                border-left: 5px solid #ef4444; 
                                padding: 12px; 
                                margin-bottom: 8px; 
                                border-radius: 4px; 
                                display: flex; 
                                align-items: start;
                                font-size: 0.95em;
                            ">
                                <div style="color: #ef4444; margin-right: 10px; font-weight: bold; font-size: 1.1em;">
                                    •
                                </div>
                                <div style="color: #7f1d1d; line-height: 1.4;">
                                    ${err}
                                </div>
                            </div>
                        `).join('');
                        }

                        Swal.fire({
                            icon: 'warning',
                            title: res.title ??
                                'Jadwal Bentrok',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Cek Jadwal Kembali',
                            width: '32rem',
                            html: `
                                <div style="text-align: left; font-family: sans-serif;">
                                    <p style="margin-bottom: 15px; color: #4b5563; font-size: 1.05em;">
                                        ${res.message}
                                    </p>
                                    
                                    <div style="
                                        max-height: 250px; 
                                        overflow-y: auto; 
                                        padding-right: 5px;
                                        scrollbar-width: thin;
                                        scrollbar-color: #cbd5e1 transparent;
                                    ">
                                        ${errorListHtml}
                                    </div>
                                </div>
                            `,
                            didOpen: () => {
                                Swal.getConfirmButton().blur();
                            }
                        });
                    }
                },
                error: function(xhr) {
                    hideModal();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat mengirim data.'
                    });
                }
            });
        });
    </script>
@endpush
