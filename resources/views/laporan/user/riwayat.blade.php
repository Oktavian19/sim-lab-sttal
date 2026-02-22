@extends('layouts.app')

@section('title', 'Riwayat Pelaporan Kerusakan - SIM LAB STTAL Admin')
@section('header', 'Riwayat Pelaporan Kerusakan')

@section('content')
    <div class="flex-1 flex flex-col overflow-hidden relative">
        <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">
            <!-- Controls: Tabs & Search -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <!-- Tabs -->
                <div
                    class="flex space-x-1 bg-white p-1 rounded-lg border border-gray-200 shadow-sm w-full sm:w-auto overflow-x-auto">
                    <button onclick="setTab('all')" id="tab-all"
                        class="tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors whitespace-nowrap bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-200">
                        Semua
                    </button>
                    <button onclick="setTab('menunggu')" id="tab-menunggu"
                        class="tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors whitespace-nowrap text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                        Menunggu
                    </button>
                    <button onclick="setTab('sedang_diperbaiki')" id="tab-sedang_diperbaiki"
                        class="tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors whitespace-nowrap text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                        Diproses
                    </button>
                    <button onclick="setTab('selesai')" id="tab-selesai"
                        class="tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors whitespace-nowrap text-gray-500 hover:text-gray-700 hover:bg-gray-50">
                        Selesai/Afkir
                    </button>
                </div>

                <!-- Search -->
                <div class="relative w-full sm:w-64">
                    <input type="text" id="searchInput" placeholder="Cari nama alat..."
                        class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
                    <i class="fa-solid fa-magnifying-glass w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
                </div>
            </div>

            <!-- List Container -->
            <div id="reportListContainer" class="space-y-4">
                <!-- Content rendered by JS -->
            </div>

            <!-- Empty State -->
            <div id="emptyState"
                class="hidden text-center py-12 bg-white rounded-xl border border-dashed border-gray-300 shadow-sm">
                <i class="fa-solid fa-clipboard-check text-4xl text-gray-300 mb-3 block"></i>
                <h3 class="text-lg font-medium text-gray-900">Belum ada laporan kerusakan</h3>
                <p class="text-gray-500 text-sm mt-1">Anda belum melaporkan kerusakan alat, atau coba ubah filter
                    pencarian.</p>
                <a href="{{ url('laporan/create') }}"
                    class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    Buat Laporan Baru
                </a>
            </div>
        </main>
    </div>

    {{-- TODO: Modal Detail Laporan Split to diff file --}}
    <div id="detailModal"
        class="modal opacity-0 pointer-events-none fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm modal-overlay"></div>

        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-3xl relative z-10 overflow-hidden flex flex-col max-h-[90vh] modal-container transform scale-95 transition-transform">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        Detail Laporan
                    </h3>
                </div>
                <button onclick="closeModal()" class="p-2 rounded-full hover:bg-gray-200 transition-colors">
                    <i class="fa-solid fa-xmark text-gray-500 text-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-0 overflow-y-auto" id="modalBody">
                <!-- Content injected by JS -->
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // --- MOCK DATA PELAPORAN KERUSAKAN ---
        const MOCK_REPORTS = @json($laporan);

        // --- STATE & UTILS ---
        let activeTab = 'all';
        let searchTerm = '';

        const formatDate = (dateStr) => {
            const date = new Date(dateStr);
            const options = {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return date.toLocaleDateString('id-ID', options) + ' WIB';
        };

        const getStatusData = (status) => {
            const data = {
                menunggu: {
                    label: "Menunggu Tindakan",
                    color: "bg-yellow-100 text-yellow-800 border-yellow-200",
                    icon: "fa-clock"
                },
                sedang_diperbaiki: {
                    label: "Sedang Diperbaiki",
                    color: "bg-blue-100 text-blue-800 border-blue-200",
                    icon: "fa-screwdriver-wrench"
                },
                selesai: {
                    label: "Selesai Diperbaiki",
                    color: "bg-green-100 text-green-800 border-green-200",
                    icon: "fa-check"
                },
                afkir: {
                    label: "Rusak Total / Afkir",
                    color: "bg-red-100 text-red-800 border-red-200",
                    icon: "fa-ban"
                },
            };
            return data[status] || data['menunggu'];
        };

        // --- RENDER LOGIC ---
        function renderList() {
            const container = document.getElementById('reportListContainer');
            const emptyState = document.getElementById('emptyState');
            container.innerHTML = '';

            const filtered = MOCK_REPORTS.filter(report => {
                if (activeTab === 'menunggu' && report.status_tindak_lanjut !== 'menunggu') return false;
                if (activeTab === 'sedang_diperbaiki' && report.status_tindak_lanjut !== 'sedang_diperbaiki')
                    return false;
                if (activeTab === 'selesai' && !['selesai', 'afkir'].includes(report.status_tindak_lanjut))
                    return false;

                if (searchTerm) {
                    const term = searchTerm.toLowerCase();
                    const alatName = report.alat.nama_alat.toLowerCase();
                    const merk = report.alat.merk.toLowerCase();
                    return alatName.includes(term) || merk.includes(term);
                }
                return true;
            });

            if (filtered.length === 0) {
                emptyState.classList.remove('hidden');
            } else {
                emptyState.classList.add('hidden');
                filtered.forEach(report => {
                    const statusInfo = getStatusData(report.status_tindak_lanjut);

                    const html = `
                        <div onclick="openModal(${report.id})" class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all cursor-pointer group flex flex-col sm:flex-row overflow-hidden">
                            <!-- Thumbnail -->
                            <div class="sm:w-48 h-32 sm:h-auto bg-gray-100 relative overflow-hidden flex-shrink-0">
                                ${report.foto_bukti 
                                    ? `<img src="/storage/${report.foto_bukti}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="Foto Rusak">` 
                                    : `<div class="w-full h-full flex flex-col items-center justify-center bg-gray-200 text-gray-400">
                                                <i class="fa-solid fa-image text-3xl mb-2"></i>
                                                <span class="text-sm">Tidak ada foto</span>
                                        </div>`
                                }
                            </div>
                            
                            <!-- Content -->
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-2 mb-2">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                ${report.alat.nama_alat}
                                            </h3>
                                            <p class="text-xs text-gray-500">${report.alat.merk} • ${report.alat.laboratorium.nama_lab}</p>
                                        </div>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border ${statusInfo.color} h-fit whitespace-nowrap">
                                            <i class="fa-solid ${statusInfo.icon}"></i> ${statusInfo.label}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 line-clamp-2 mt-2">
                                        "${report.deskripsi_kerusakan}"
                                    </p>
                                </div>
                                
                                <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-xs text-gray-400 flex items-center gap-1">
                                        <i class="fa-regular fa-calendar"></i> Dilaporkan: ${formatDate(report.tanggal_lapor)}
                                    </span>
                                    <span class="text-sm text-blue-600 font-medium group-hover:translate-x-1 transition-transform flex items-center gap-1">
                                        Lihat Detail <i class="fa-solid fa-chevron-right text-xs"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    `;
                    container.innerHTML += html;
                });
            }
        }

        function setTab(tab) {
            activeTab = tab;
            document.querySelectorAll('.tab-btn').forEach(btn => {
                if (btn.id === `tab-${tab}`) {
                    btn.className =
                        "tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors whitespace-nowrap bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-200";
                } else {
                    btn.className =
                        "tab-btn px-4 py-2 text-sm font-medium rounded-md transition-colors whitespace-nowrap text-gray-500 hover:text-gray-700 hover:bg-gray-50";
                }
            });
            renderList();
        }

        // --- SEARCH HANDLER ---
        document.getElementById('searchInput').addEventListener('keyup', (e) => {
            searchTerm = e.target.value;
            renderList();
        });

        // --- MODAL LOGIC ---
        const modal = document.getElementById('detailModal');
        const modalOverlay = modal.querySelector('.modal-overlay');

        function openModal(id) {
            const report = MOCK_REPORTS.find(r => r.id === id);
            if (!report) return;

            const statusInfo = getStatusData(report.status_tindak_lanjut);

            const modalBody = document.getElementById('modalBody');

            modalBody.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-5 bg-white">
                    <!-- Kolom Kiri: Foto -->
                    <div class="md:col-span-2 bg-gray-100 border-r border-gray-200">
                        <img src="/storage/${report.foto_bukti}" alt="Foto Bukti" class="w-full h-48 md:h-full object-cover">
                    </div>
                    
                    <!-- Kolom Kanan: Detail -->
                    <div class="md:col-span-3 p-6 sm:p-8 flex flex-col h-full">
                        
                        <!-- Alat & Lokasi -->
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">${report.alat.nama_alat}</h2>
                            <p class="text-sm text-gray-500 mt-1">${report.alat.merk}</p>
                            <div class="flex items-center gap-2 mt-3">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-medium flex items-center gap-1 border border-gray-200">
                                    <i class="fa-solid fa-location-dot"></i> ${report.alat.laboratorium.nama_lab}
                                </span>
                                <span class="text-xs text-gray-400 flex items-center gap-1">
                                    <i class="fa-regular fa-clock"></i> ${formatDate(report.tanggal_lapor)}
                                </span>
                            </div>
                        </div>

                        <!-- Deskripsi Laporan -->
                        <div class="mb-6">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Kerusakan Laporan Anda</h4>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-sm text-gray-700 leading-relaxed">
                                "${report.deskripsi_kerusakan}"
                            </div>
                        </div>

                        <!-- Tindak Lanjut Admin -->
                        <div class="mt-auto border-t border-gray-200 pt-6">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Tindak Lanjut Admin</h4>
                            <div class="flex items-start gap-4">
                                <div class="mt-1">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border ${statusInfo.color}">
                                        <i class="fa-solid ${statusInfo.icon}"></i>
                                    </span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">${statusInfo.label}</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        ${report.keterangan_perbaikan 
                                            ? `<i>"${report.keterangan_perbaikan}"</i>` 
                                            : "Admin belum memberikan catatan perbaikan."}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Show Modal
            modal.classList.remove('opacity-0', 'pointer-events-none');
            const container = modal.querySelector('.modal-container');
            container.classList.remove('scale-95');
            container.classList.add('scale-100');
            document.body.classList.add('modal-active');
        }

        function closeModal() {
            modal.classList.add('opacity-0', 'pointer-events-none');
            const container = modal.querySelector('.modal-container');
            container.classList.remove('scale-100');
            container.classList.add('scale-95');
            document.body.classList.remove('modal-active');
        }

        modalOverlay.addEventListener('click', closeModal);

        // Init
        renderList();
    </script>
@endpush
