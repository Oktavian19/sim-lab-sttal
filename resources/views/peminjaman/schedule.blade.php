@extends('layouts.app')

@section('title', 'Jadwal Peminjaman - SIM LAB STTAL Admin')
@section('header', 'Jadwal Peminjaman')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">
        <!-- Controls Section -->
        <div
            class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 mb-6 flex flex-col md:flex-row justify-between items-center gap-4">

            <!-- View Mode Toggle -->
            <div class="flex bg-gray-100 p-1 rounded-lg">
                <button onclick="setViewMode('lab')" id="btn-lab"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all bg-white text-blue-600 shadow-sm">
                    Jadwal Laboratorium
                </button>
                <button onclick="setViewMode('alat')" id="btn-alat"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-all text-gray-500 hover:text-gray-700">
                    Jadwal Alat
                </button>
            </div>

            <!-- Date Picker -->
            <div class="flex items-center gap-3 bg-white border border-gray-300 rounded-lg px-2 py-1 shadow-sm">
                <button onclick="changeDate(-1)" class="p-1.5 hover:bg-gray-100 rounded text-slate-500 transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <div class="flex items-center gap-2 px-2 border-x border-gray-100">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    <input type="date" id="datePicker"
                        class="text-sm font-medium text-slate-700 focus:outline-none border-none bg-transparent cursor-pointer">
                </div>
                <button onclick="changeDate(1)" class="p-1.5 hover:bg-gray-100 rounded text-slate-500 transition">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>

            <!-- Legend -->
            <div class="flex gap-3 text-xs hidden lg:flex">
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 bg-green-200 rounded border border-green-300"></div>
                    <span class="text-slate-600">Disetujui</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 bg-yellow-100 rounded border border-yellow-200"></div>
                    <span class="text-slate-600">Pending</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div class="w-3 h-3 bg-white rounded border border-gray-200"></div>
                    <span class="text-slate-600">Kosong</span>
                </div>
            </div>
        </div>

        <!-- Timeline Grid -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1000px]">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th
                                class="min-w-64 px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-64 sticky left-0 bg-gray-50 z-20 border-r border-gray-200 shadow-sm">
                                <span id="resourceHeader">Nama Laboratorium</span>
                            </th>
                            <script>
                                for (let i = 0; i <= 23; i++) {
                                    document.write(
                                        `<th class="px-2 py-3 text-center text-xs font-medium text-slate-400 w-24 border-r border-gray-100 last:border-0">${i.toString().padStart(2, '0')}:00</th>`
                                    );
                                }
                            </script>
                        </tr>
                    </thead>
                    <tbody id="schedulerBody" class="divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 flex flex-col md:flex-row gap-4 bg-blue-50 p-4 rounded-lg border border-blue-100">
            <div class="flex-shrink-0 pt-1">
                <i class="fa-solid fa-circle-info text-blue-600 text-lg"></i>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-blue-800">Tips Pengajuan Peminjaman</h3>
                <p class="text-sm text-blue-600 mt-1 leading-relaxed">
                    Untuk mengajukan peminjaman baru, pastikan slot waktu pada baris Lab atau Alat yang diinginkan
                    berwarna putih (Kosong).
                    Hindari jadwal berwarna Hijau (Sudah disetujui) atau Kuning (Sedang diproses). Lab/Alat yang sedang
                    "Perbaikan" tidak dapat dipinjam.
                </p>
            </div>
            <div class="flex-shrink-0 md:ml-auto self-center">
                <a href="{{ route('peminjaman.create') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-colors">
                    <i class="fa-solid fa-plus"></i> Buat Pengajuan
                </a>
            </div>
        </div>

    </main>
@endsection

@push('scripts')
    <script>
        // --- MOCK DATA ---
        const MOCK_LABS = @json($labs);
        const MOCK_ALAT = @json($alats);
        const MOCK_PEMINJAMAN = @json($peminjamans);

        // --- STATE & CONFIG ---
        let viewMode = 'lab';
        let selectedDate = new Date();

        const hours = Array.from({
            length: 24
        }, (_, i) => i);

        // --- INIT ---
        document.addEventListener('DOMContentLoaded', () => {
            updateDatePicker();
            renderGrid();

            document.getElementById('datePicker').addEventListener('change', (e) => {
                selectedDate = new Date(e.target.value);
                renderGrid();
            });
        });

        // --- FUNCTIONS ---

        function updateDatePicker() {
            const year = selectedDate.getFullYear();
            const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
            const day = String(selectedDate.getDate()).padStart(2, '0');
            document.getElementById('datePicker').value = `${year}-${month}-${day}`;
        }

        function setViewMode(mode) {
            viewMode = mode;

            const btnLab = document.getElementById('btn-lab');
            const btnAlat = document.getElementById('btn-alat');
            const header = document.getElementById('resourceHeader');

            const activeClass = "px-4 py-2 rounded-md text-sm font-medium transition-all bg-white text-blue-600 shadow-sm";
            const inactiveClass =
                "px-4 py-2 rounded-md text-sm font-medium transition-all text-gray-500 hover:text-gray-700";

            if (mode === 'lab') {
                btnLab.className = activeClass;
                btnAlat.className = inactiveClass;
                header.innerText = "Nama Laboratorium";
            } else {
                btnLab.className = inactiveClass;
                btnAlat.className = activeClass;
                header.innerText = "Nama Alat";
            }
            renderGrid();
        }

        function changeDate(days) {
            selectedDate.setDate(selectedDate.getDate() + days);
            updateDatePicker();
            renderGrid();
        }

        function checkAvailability(resourceId, hour, type) {
            const year = selectedDate.getFullYear();
            const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
            const day = String(selectedDate.getDate()).padStart(2, '0');
            const selectedDateStr = `${year}-${month}-${day}`;

            return MOCK_PEMINJAMAN.find(b => {
                const bookingStart = new Date(b.start_time);
                const bookingEnd = new Date(b.end_time);

                const bookingDateStr = bookingStart.toISOString().split('T')[0];

                if (bookingDateStr !== selectedDateStr) return false;

                let isResourceMatch = false;
                if (type === 'lab') {
                    isResourceMatch = (b.id_lab == resourceId);
                } else {
                    if (Array.isArray(b.alat_dipinjam)) {
                        isResourceMatch = b.alat_dipinjam.includes(resourceId);
                    } else {
                        isResourceMatch = JSON.parse(b.alat_dipinjam || '[]').includes(resourceId);
                    }
                }
                if (!isResourceMatch) return false;

                const startHour = bookingStart.getHours();

                let endHour = bookingEnd.getHours();

                if (bookingEnd.getMinutes() > 0 || bookingEnd.getSeconds() > 0) {
                    endHour += 1;
                }

                return hour >= startHour && hour < endHour;
            });
        }

        function renderGrid() {
            const tbody = document.getElementById('schedulerBody');
            tbody.innerHTML = '';

            const resources = viewMode === 'lab' ? MOCK_LABS : MOCK_ALAT;

            if (!resources || resources.length === 0) {
                tbody.innerHTML =
                    `<tr><td colspan="25" class="px-6 py-10 text-center text-gray-500">Tidak ada data resource.</td></tr>`;
                return;
            }

            resources.forEach(res => {
                const tr = document.createElement('tr');
                tr.className = "hover:bg-slate-50/50 transition-colors group/row";

                const isMaintenance = (viewMode === 'lab' && res.status === 'perbaikan') ||
                    (viewMode === 'alat' && res.kondisi === 'rusak');

                let badgeHTML = '';
                let subText = '';

                if (viewMode === 'lab') {
                    const isActive = res.status === 'aktif';
                    badgeHTML =
                        `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase ${isActive ? 'bg-emerald-100 text-emerald-800' : 'bg-orange-100 text-orange-800'}">${res.status}</span>`;
                    subText = `<i class="fa-solid fa-users text-slate-400 mr-1"></i> ${res.kapasitas || 0} Org`;
                } else {
                    const isGood = res.kondisi === 'baik';
                    badgeHTML =
                        `<span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase ${isGood ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800'}">${res.kondisi}</span>`;
                    subText = res.merk || '-';
                }

                const infoCell = `
                    <td class="px-6 py-4 sticky left-0 bg-white z-10 border-r border-gray-200 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)]">
                        <div class="flex flex-col">
                            <span class="font-semibold text-slate-800 text-sm truncate w-48" title="${viewMode === 'lab' ? res.nama_lab : res.nama_alat}">
                                ${viewMode === 'lab' ? res.nama_lab : res.nama_alat}
                            </span>
                            <div class="flex items-center gap-2 mt-1">
                                ${badgeHTML}
                                <span class="text-xs text-slate-500 flex items-center truncate">${subText}</span>
                            </div>
                        </div>
                    </td>
                `;
                tr.innerHTML += infoCell;

                hours.forEach(hour => {
                    if (isMaintenance) {
                        tr.innerHTML += `
                            <td class="p-1 h-20 border-r border-gray-100 bg-gray-50 cursor-not-allowed">
                                <div class="w-full h-full rounded bg-gray-200/50 flex items-center justify-center border border-dashed border-gray-300" title="Sedang Perbaikan">
                                    <i class="fa-solid fa-screwdriver-wrench text-gray-400 text-xs"></i>
                                </div>
                            </td>`;
                        return;
                    }

                    const booking = checkAvailability(res.id, hour, viewMode);

                    if (booking) {
                        const isApproved = booking.status_pengajuan === 'disetujui';
                        const colorClass = isApproved ?
                            'bg-green-100 border-green-200 text-green-800 hover:bg-green-200' :
                            'bg-yellow-50 border-yellow-200 text-yellow-800 hover:bg-yellow-100';

                        tr.innerHTML += `
                            <td class="p-1 h-20 border-r border-gray-100 relative group cursor-pointer" title="${booking.kegiatan} oleh ${booking.peminjam_nama}">
                                <div class="w-full h-full rounded border px-1 py-1 text-[10px] leading-tight overflow-hidden ${colorClass} transition-colors shadow-sm">
                                    <div class="font-bold truncate">${booking.kegiatan}</div>
                                    <div class="truncate opacity-75 text-[9px] mt-0.5">${booking.peminjam_nama || 'User'}</div>
                                </div>
                            </td>`;
                    } else {
                        tr.innerHTML += `
                            <td class="p-1 h-20 border-r border-gray-100 relative hover:bg-blue-50 transition-colors cursor-pointer group-cell">
                                <div class="hidden group-hover/row:flex group-hover/row:items-center group-hover/row:justify-center w-full h-full text-slate-300 text-[10px]">
                                    
                                </div>
                            </td>`;
                    }
                });

                tbody.appendChild(tr);
            });
        }
    </script>
@endpush
