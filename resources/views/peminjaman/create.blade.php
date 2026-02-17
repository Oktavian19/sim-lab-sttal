@extends('layouts.app')

@section('title', 'Formulir Peminjaman - SIM LAB STTAL Admin')
@section('header', 'Formulir Peminjaman')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">

        <div class="mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Formulir Peminjaman</h2>
                <p class="text-sm text-gray-500 mt-1">Isi detail kegiatan untuk meminjam fasilitas laboratorium atau
                    alat.</p>
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
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="kegiatan" required placeholder="Contoh: Praktikum Basis Data Lanjut"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm">
                            <p id="error-kegiatan" class="mt-1 text-sm text-red-600"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mulai <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="start_time" id="start_time" required
                                    class="bg-white w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm"
                                    placeholder="Pilih waktu mulai...">
                            </div>
                            <p id="error-start_time" class="mt-1 text-sm text-red-600"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Selesai <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="end_time" id="end_time" required
                                    class="bg-white w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm"
                                    placeholder="Pilih waktu selesai...">
                            </div>
                            <p id="error-end_time" class="mt-1 text-sm text-red-600"></p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi Peserta <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="jumlah_peserta" min="1" required
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition text-sm"
                                placeholder="0">
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
                        Lokasi (Laboratorium)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($labs as $lab)
                            <label class="cursor-pointer group">
                                <input type="radio" name="id_lab" value="{{ $lab->id }}" class="peer sr-only">
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
                        <label class="cursor-pointer group">
                            <input type="radio" name="id_lab" value="" class="peer sr-only" checked>
                            <div
                                class="relative flex flex-col p-4 border border-gray-200 rounded-xl hover:border-blue-300 transition-all bg-white h-full peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:ring-2 peer-checked:ring-blue-100">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-semibold text-gray-900 text-sm">Tanpa Ruangan</span>
                                </div>
                                <p class="text-xs text-gray-500 mb-4">Pilih opsi ini jika Anda hanya ingin meminjam alat
                                    tanpa membooking ruangan.</p>
                                <div class="mt-auto flex items-center gap-2 text-xs text-gray-600">
                                    <i class="fa-solid fa-toolbox text-gray-400"></i> Hanya Alat
                                </div>
                            </div>
                        </label>
                    </div>
                </section>

                <hr class="border-gray-100" />

                <section class="space-y-4">
                    <div class="flex justify-between items-center flex-wrap gap-2">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500 flex items-center gap-2">
                            <div
                                class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                3</div>
                            Peminjaman Alat (Opsional)
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
                        <input type="text" id="toolSearch" placeholder="Cari nama alat..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>

                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-lg divide-y divide-gray-100 bg-white"
                        id="toolsContainer">

                        @foreach ($alats as $alat)
                            <div class="tool-item flex items-center justify-between p-3 hover:bg-gray-50 transition cursor-pointer group"
                                onclick="toggleTool(this, '{{ $alat->id }}')">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="checkbox-indicator w-5 h-5 rounded border border-gray-300 flex items-center justify-center transition bg-white text-white group-hover:border-blue-400">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800 tool-name">{{ $alat->nama_alat }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $alat->merk }} • Lokasi:
                                            {{ $alat->laboratorium->nama_lab }}
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-medium">Baik</span>
                                <input type="checkbox" name="alat_ids[]" value="{{ $alat->id }}"
                                    class="hidden tool-checkbox">
                            </div>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-right">*Hanya menampilkan alat dengan kondisi Baik
                    </p>
                </section>

                <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                    <button type="button"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium text-sm transition">Batal</button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm hover:shadow-md font-medium text-sm flex items-center gap-2 transition">
                        <i class="fa-solid fa-paper-plane"></i> Ajukan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        function toggleTool(element, id) {
            const checkbox = element.querySelector('.tool-checkbox');
            const indicator = element.querySelector('.checkbox-indicator');

            checkbox.checked = !checkbox.checked;

            if (checkbox.checked) {
                element.classList.add('bg-blue-50');
                indicator.classList.remove('bg-white', 'border-gray-300');
                indicator.classList.add('bg-blue-600', 'border-blue-600');
            } else {
                element.classList.remove('bg-blue-50');
                indicator.classList.add('bg-white', 'border-gray-300');
                indicator.classList.remove('bg-blue-600', 'border-blue-600');
            }

            updateToolCount();
        }

        function updateToolCount() {
            const checkedCount = document.querySelectorAll('.tool-checkbox:checked').length;
            const badge = document.getElementById('tool-count-badge');
            badge.innerText = `${checkedCount} alat dipilih`;
        }

        document.getElementById('toolSearch').addEventListener('keyup', function(e) {
            const filter = e.target.value.toLowerCase();
            const items = document.querySelectorAll('.tool-item');

            items.forEach(item => {
                const name = item.querySelector('.tool-name').innerText.toLowerCase();
                if (name.includes(filter)) {
                    item.style.display = "";
                } else {
                    item.style.display = "none";
                }
            });
        });

        $(document).ready(function() {
            const startPicker = flatpickr("#start_time", {
                enableTime: true,
                enableMinutes: false,
                dateFormat: "Y-m-d H:00:00",
                altInput: true,
                altFormat: "l, d F Y H:00",
                time_24hr: true,
                locale: "id",
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    endPicker.set('minDate', dateStr);

                    $("#start_time").valid();
                }
            });

            const endPicker = flatpickr("#end_time", {
                enableTime: true,
                enableMinutes: false,
                dateFormat: "Y-m-d H:00:00",
                altInput: true,
                altFormat: "l, d F Y H:00",
                time_24hr: true,
                locale: "id",
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    $("#end_time").valid();
                }
            });

            $.validator.addMethod("greaterThan", function(value, element, param) {
                var startDate = $(param).val();
                if (!value || !startDate) return true; 
                return new Date(value) > new Date(startDate);
            }, "Waktu selesai harus setelah waktu mulai.");

            $("#loanForm").validate({
                rules: {
                    kegiatan: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    start_time: {
                        required: true,
                    },
                    end_time: {
                        required: true,
                        greaterThan: "#start_time"
                    },
                    jumlah_peserta: {
                        required: true,
                        min: 1,
                    }

                },
                messages: {
                    kegiatan: {
                        required: 'Nama Kegiatan wajib diisi.',
                        minlength: 'Minimal 3 karakter.',
                        maxlength: 'Maksimal 100 karakter.'
                    },
                    start_time: {
                        required: 'Waktu mulai wajib diisi.',
                    },
                    end_time: {
                        required: 'Waktu selesai wajib diisi.',
                        greaterThan: "Waktu selesai harus setelah waktu mulai."
                    },
                    jumlah_peserta: {
                        required: 'Jumlah peserta wajib diisi.',
                        min: 'Minimal 1 peserta.',
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
                            title: res.title ?? 'Berhasil',
                            html: `
                                <div style="font-family: sans-serif;">
                                    <p style="color: #4b5563; font-size: 1.1em; margin-bottom: 15px;">
                                        ${res.message}
                                    </p>
                                    
                                    <div style="
                                        background-color: #ecfdf5; 
                                        border: 1px solid #10b981; 
                                        border-radius: 8px; 
                                        padding: 12px;
                                        margin-top: 10px;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        gap: 10px;
                                    ">
                                        <span style="font-size: 1.5em;">⏳</span>
                                        <div style="text-align: left;">
                                            <span style="display: block; font-size: 0.85em; color: #047857; font-weight: 600;">STATUS SELANJUTNYA</span>
                                            <span style="color: #065f46; font-weight: 500;">Menunggu Persetujuan Admin</span>
                                        </div>
                                    </div>
                                </div>
                            `,
                            timer: 3000, 
                            timerProgressBar: true, 
                            showConfirmButton: false,
                        });

                        document.getElementById('loanForm').reset();

                        document.querySelectorAll('#toolsContainer .tool-checkbox')
                            .forEach(cb => cb.checked = false);

                        document.querySelectorAll('#toolsContainer .tool-item')
                            .forEach(item => {
                                item.classList.remove('bg-blue-50',
                                'border-blue-200'); 

                                const indicator = item.querySelector('.checkbox-indicator');
                                if (indicator) {
                                    indicator.classList.remove('bg-blue-500', 'border-blue-500');
                                    indicator.classList.add('bg-white', 'border-gray-300');
                                    indicator.innerHTML = ''; 
                                }
                            });
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
                                        /* Custom Scrollbar styling untuk webkit */
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
