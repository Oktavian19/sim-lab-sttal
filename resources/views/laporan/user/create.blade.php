@extends('layouts.app')

@section('title', 'Formulir Pelaporan Kerusakan - SIM LAB STTAL Admin')
@section('header', 'Formulir Pelaporan Kerusakan')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">
        <div class="mx-auto bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Formulir Pelaporan Kerusakan</h2>
                <p class="text-sm text-gray-500 mt-1">Laporkan alat laboratorium yang mengalami kendala atau
                    kerusakan agar segera ditindaklanjuti.</p>
            </div>

            <form id="laporForm" action="{{ url('laporan/store') }}" method="POST" class="p-6 sm:p-8 space-y-8 validate">
                @csrf
                <input type="hidden" name="alat_id" id="alatIdInput">
                <section>
                    <div class="flex items-center gap-2 mb-4">
                        <span
                            class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold shadow-inner">1</span>
                        <h2 class="text-lg font-semibold text-gray-800">Pilih Lokasi (Laboratorium)</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="labContainer">

                    </div>
                </section>

                <hr class="border-gray-100">

                <section id="step2" class="transition-all duration-500 opacity-50 pointer-events-none">
                    <div class="flex items-center gap-2 mb-4">
                        <span
                            class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold shadow-inner">2</span>
                        <h2 class="text-lg font-semibold text-gray-800">Pilih Alat yang Rusak</h2>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="p-3 border-b border-gray-200 bg-gray-50 flex items-center gap-2">
                            <i class="fa-solid fa-magnifying-glass text-gray-400 ml-2"></i>
                            <input type="text" id="searchTool" placeholder="Cari nama atau merk alat..."
                                class="w-full bg-transparent border-none focus:ring-0 text-sm outline-none text-gray-700 placeholder-gray-400 py-1"
                                onkeyup="filterTools()" />
                        </div>

                        <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 bg-white custom-scrollbar"
                            id="toolsContainer">
                            <div class="p-8 text-center text-sm text-gray-500 flex flex-col items-center gap-2">
                                <i class="fa-solid fa-circle-exclamation text-gray-300 text-2xl"></i>
                                Silakan pilih lokasi (Laboratorium) terlebih dahulu.
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100">

                <section id="step3" class="transition-all duration-500 opacity-50 pointer-events-none">
                    <div class="flex items-center gap-2 mb-4">
                        <span
                            class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-600 text-xs font-bold shadow-inner">3</span>
                        <h2 class="text-lg font-semibold text-gray-800">Detail Kerusakan</h2>
                    </div>

                    <div class="space-y-6 bg-white p-5 sm:p-6 border border-gray-200 rounded-xl shadow-sm">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Kerusakan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="4" required
                                placeholder="Jelaskan secara detail kerusakan yang terjadi... (contoh: Layar bergaris warna hijau saat dinyalakan lebih dari 10 menit)"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none text-sm transition-shadow resize-none"></textarea>
                            <p id="error-deskripsi" class="text-red-500 text-sm mt-1 hidden"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Bukti Kerusakan <span class="text-red-500">*</span>
                            </label>

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-red-400 transition-colors relative bg-gray-50"
                                id="dropZone">
                                <div class="space-y-2 text-center" id="uploadState">
                                    <div class="flex flex-col items-center">
                                        <div class="p-3 bg-red-100 rounded-full text-red-500 mb-3 shadow-sm">
                                            <i class="fa-regular fa-image text-2xl"></i>
                                        </div>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label
                                                class="relative cursor-pointer bg-transparent rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-red-500 px-1">
                                                <span>Upload file</span>
                                                <input type="file" id="fotoInput" name="foto" class="sr-only"
                                                    accept="image/*" onchange="handlePhotoChange(event)" required />
                                            </label>
                                            <p className="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG, JPEG (Maks. 2MB)</p>
                                        <p id="error-fotoInput" class="text-red-500 text-sm mt-1 hidden"></p>
                                    </div>
                                </div>

                                <div id="previewState" class="hidden text-center">
                                    <div class="relative inline-block">
                                        <img id="imagePreview" src="" alt="Preview"
                                            class="mx-auto h-32 w-auto rounded-lg object-cover shadow-md border border-gray-200" />
                                        <button type="button" onclick="removePhoto()"
                                            class="absolute -top-3 -right-3 bg-white text-red-600 rounded-full p-1.5 shadow-md border border-red-100 hover:bg-red-50 transition-colors focus:outline-none"
                                            title="Hapus Foto">
                                            <i class="fa-solid fa-xmark w-4 h-4 flex items-center justify-center"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="pt-6 border-t border-gray-100 flex flex-col-reverse sm:flex-row justify-end gap-3">
                    <button type="button" onclick="window.location.reload()"
                        class="w-full sm:w-auto px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btnSubmit" disabled
                        class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm">
                        <i class="fa-solid fa-paper-plane"></i>
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        // --- MOCK DATA ---
        const laboratorium = @json($laboratorium);
        const alat = @json($alat);

        // --- STATE ---
        let selectedLabId = null;
        let selectedToolId = null;

        // --- INIT RENDER LABS ---
        function renderLabs() {
            const container = document.getElementById('labContainer');
            container.innerHTML = '';

            laboratorium.forEach(lab => {
                const isSelected = selectedLabId === lab.id;
                const borderClass = isSelected ? 'border-blue-500 bg-blue-50 shadow-sm' :
                    'border-gray-200 bg-white hover:border-blue-300 hover:bg-gray-50';
                const iconBgClass = isSelected ? 'bg-blue-200 text-blue-700' :
                    'bg-gray-100 text-gray-500 group-hover:bg-blue-100 group-hover:text-blue-600';
                const textClass = isSelected ? 'text-blue-900' : 'text-gray-800';
                const checkIcon = isSelected ?
                    `<i class="fa-solid fa-circle-check text-blue-600 absolute top-4 right-4 text-lg"></i>` : '';

                container.innerHTML += `
                    <div onclick="selectLab(${lab.id})" class="relative p-4 rounded-xl border-2 cursor-pointer transition-all duration-200 group ${borderClass}">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg transition-colors ${iconBgClass}">
                                    <i class="fa-solid fa-location-dot w-5 h-5 flex items-center justify-center"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-sm ${textClass}">${lab.nama_lab}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5 pr-4 line-clamp-2">${lab.deskripsi}</p>
                                </div>
                            </div>
                            ${checkIcon}
                        </div>
                    </div>
                `;
            });
        }

        // --- ACTIONS ---
        function selectLab(id) {
            selectedLabId = id;
            selectedToolId = null;
            document.getElementById('searchTool').value = '';

            // Re-render UI
            renderLabs();
            renderTools();

            document.getElementById('step2').classList.remove('opacity-50', 'pointer-events-none');
            document.getElementById('step3').classList.add('opacity-50', 'pointer-events-none');
            checkSubmitStatus();
        }

        function filterTools() {
            renderTools();
        }

        function renderTools() {
            const container = document.getElementById('toolsContainer');
            const searchQuery = document.getElementById('searchTool').value.toLowerCase();
            container.innerHTML = '';

            if (!selectedLabId) return;

            const filtered = alat.filter(alat => {
                const matchLab = alat.lokasi === selectedLabId;
                const matchSearch = alat.nama_alat.toLowerCase().includes(searchQuery) ||
                    (alat.merk && alat.merk.toLowerCase().includes(searchQuery));
                return matchLab && matchSearch;
            });

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="p-8 text-center text-sm text-gray-500 flex flex-col items-center justify-center h-32">
                        <i class="fa-solid fa-box-open text-gray-300 text-2xl mb-2"></i>
                        Alat tidak ditemukan.
                    </div>`;
                return;
            }

            filtered.forEach(alat => {
                const isSelected = selectedToolId === alat.id;
                const bgClass = isSelected ? 'bg-red-50' : 'hover:bg-gray-50';
                const indicatorClass = isSelected ? 'border-red-500 bg-red-500' :
                    'border-gray-300 bg-white group-hover:border-red-400';
                const dotHtml = isSelected ? `<div class="w-2 h-2 bg-white rounded-full"></div>` : '';
                const textClass = isSelected ? 'text-red-900' : 'text-gray-800';

                container.innerHTML += `
                    <div onclick="selectTool(${alat.id})" class="flex flex-col p-4 cursor-pointer transition-colors group ${bgClass} border-b border-gray-50 last:border-0">
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center gap-4">
                                <div class="w-5 h-5 rounded-full border flex items-center justify-center transition-all shadow-inner ${indicatorClass}">
                                    ${dotHtml}
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 rounded-lg text-gray-500 hidden sm:flex items-center justify-center">
                                        <i class="fa-solid fa-desktop"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium ${textClass}">${alat.nama_alat}</p>
                                        <p class="text-[11px] text-gray-500 mt-1 flex items-center gap-1">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-gray-500 bg-gray-200/60 font-mono">
                                                ${alat.merk ? `${alat.merk}` : ''}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }

        function selectTool(id) {
            document.getElementById('alatIdInput').value = id;
            selectedToolId = id;
            renderTools();

            document.getElementById('step3').classList.remove('opacity-50', 'pointer-events-none');
            checkSubmitStatus();
        }

        // --- PHOTO HANDLING ---
        function handlePhotoChange(event) {
            const file = event.target.files[0];
            const errorFoto = document.getElementById('error-fotoInput');

            if (file) {
                if (file.size > 2097152) {
                    removePhoto();
                    errorFoto.textContent = 'Ukuran file terlalu besar. Maksimal 2MB.';
                    errorFoto.classList.remove('hidden');
                    return;
                } else {
                    errorFoto.classList.add('hidden');
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('uploadState').classList.add('hidden');
                    document.getElementById('previewState').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
            checkSubmitStatus();
        }

        function removePhoto() {
            document.getElementById('fotoInput').value = '';
            document.getElementById('imagePreview').src = '';
            document.getElementById('uploadState').classList.remove('hidden');
            document.getElementById('previewState').classList.add('hidden');
            document.getElementById('error-fotoInput').classList.add('hidden');
            checkSubmitStatus();
        }

        document.getElementById('deskripsi').addEventListener('input', checkSubmitStatus);

        function checkSubmitStatus() {
            const btnSubmit = document.getElementById('btnSubmit');
            const deskripsi = document.getElementById('deskripsi').value.trim();
            const photo = document.getElementById('fotoInput').files.length > 0;
            const errorFoto = document.getElementById('error-fotoInput');
            const errorDeskripsi = document.getElementById('error-deskripsi');

            let isDeskripsiValid = true;

            if (deskripsi.length > 0 && deskripsi.length < 10) {
                errorDeskripsi.textContent = 'Deskripsi harus berisi minimal 10 karakter.';
                errorDeskripsi.classList.remove('hidden');
                isDeskripsiValid = false;
            } else {
                errorDeskripsi.classList.add('hidden');
                if (deskripsi.length === 0) isDeskripsiValid = false;
            }

            if (selectedToolId && deskripsi.length >= 10 && !photo) {
                errorFoto.textContent = 'Foto bukti kerusakan wajib diunggah.';
            }

            btnSubmit.disabled = !(selectedToolId && isDeskripsiValid && photo);
        }

        // --- RESET FORM ---
        function resetFormUI() {
            document.getElementById('laporForm').reset();

            selectedLabId = null;
            selectedToolId = null;
            if (document.getElementById('alatIdInput')) {
                document.getElementById('alatIdInput').value = '';
            }

            removePhoto();

            document.getElementById('error-deskripsi').classList.add('hidden');

            document.getElementById('step2').classList.add('opacity-50', 'pointer-events-none');
            document.getElementById('step3').classList.add('opacity-50', 'pointer-events-none');

            renderLabs();
            renderTools();

            document.getElementById('btnSubmit').disabled = true;
            document.getElementById('btnSubmit').innerHTML =
                '<i class="fa-solid fa-paper-plane"></i> Kirim Laporan';
        }
        
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
                beforeSend: function() {
                    form.find('button[type="submit"]').prop('disabled', true).text('Menyimpan...');
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
                        resetFormUI();
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
                    hideModal();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat mengirim data.'
                    });
                }
            });
        });

        renderLabs();
    </script>
@endpush
