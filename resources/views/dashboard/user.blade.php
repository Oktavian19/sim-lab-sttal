@extends('layouts.app')

@section('title', 'Dashboard - SIM LAB STTAL')
@section('header', 'Dashboard')

@section('content')
    <!-- Scrollable Content -->
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <!-- Welcome Card -->
        <div
            class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-lg shadow-lg p-6 mb-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-2xl font-bold mb-2">Selamat Siang, {{ auth()->user()->pangkat }}
                    {{ auth()->user()->nama }}!</h3>
                <p class="text-blue-100 max-w-2xl">
                    Selamat datang di SIM LAB STTAL. Anda dapat memantau jadwal praktikum, meminjam alat, dan melaporkan
                    kendala fasilitas di sini.
                </p>
                <div class="mt-6 flex gap-3">
                    <a href="#"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md shadow-blue-200 transition-all">
                        <i class="fa-solid fa-plus mr-2"></i> Pinjam Alat Baru
                    </a>
                    <a href="#"
                        class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        <i class="fa-solid fa-calendar-check mr-2"></i> Cek Jadwal Lab
                    </a>
                </div>
            </div>
            <!-- Decorative Icon -->
            <div class="absolute right-0 bottom-0 opacity-5 pointer-events-none md:block hidden">
                <i class="fa-solid fa-microscope text-[180px] text-blue-900 -mr-10 -mb-10"></i>
            </div>
        </div>

        <!-- Status Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- Card 1: Status Peminjaman -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pinjaman Aktif</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1">2 <span
                                class="text-sm font-normal text-slate-500">Item</span></h3>
                    </div>
                    <div
                        class="bg-blue-50 p-2 rounded-lg text-blue-600">
                        <i class="fa-solid fa-hand-holding-hand text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-orange-500 font-semibold"><i class="fa-solid fa-circle-exclamation mr-1"></i>
                        Jatuh tempo besok</span>
                    <a href="#" class="text-xs text-blue-600 hover:underline">Lihat Detail</a>
                </div>
            </div>

            <!-- Card 2: Status Permohonan -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-400 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Menunggu Persetujuan</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1">1 <span
                                class="text-sm font-normal text-slate-500">Request</span></h3>
                    </div>
                    <div
                        class="bg-yellow-50 p-2 rounded-lg text-yellow-600">
                        <i class="fa-solid fa-clock text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t border-slate-100">
                    <p class="text-xs text-slate-500">Pengajuan Lab Elektronika</p>
                </div>
            </div>

            <!-- Card 3: Status Tanggungan -->
            <div
                class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Status Akun</p>
                        <h3 class="text-lg font-bold text-green-600 mt-2">AKTIF</h3>
                    </div>
                    <div
                        class="bg-green-50 p-2 rounded-lg text-green-600">
                        <i class="fa-solid fa-user-check text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t border-slate-100">
                    <p class="text-xs text-slate-500">Tidak ada denda atau sanksi.</p>
                </div>
            </div>

        </div>

        <!-- Content Grid: Active Loans & Notifications -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Daftar Pinjaman (Lebar) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tabel Pinjaman Berjalan -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                        <h3 class="font-semibold text-slate-800"><i
                                class="fa-solid fa-list-check text-blue-500 mr-2"></i>Pinjaman Saya Saat Ini</h3>
                        <span class="text-xs font-medium px-2.5 py-0.5 rounded bg-blue-100 text-blue-800">2 Item</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 font-medium">Nama Alat</th>
                                    <th class="px-6 py-3 font-medium">Kode Inventaris</th>
                                    <th class="px-6 py-3 font-medium">Tgl Pinjam</th>
                                    <th class="px-6 py-3 font-medium">Tenggat Waktu</th>
                                    <th class="px-6 py-3 font-medium text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <!-- Item 1 -->
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-800">Oscilloscope Digital Rigol</td>
                                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">LAB-EL-004</td>
                                    <td class="px-6 py-4 text-slate-600">25 Jan 2026</td>
                                    <td class="px-6 py-4 text-red-600 font-medium">27 Jan 2026</td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                            Dipakai
                                        </span>
                                    </td>
                                </tr>
                                <!-- Item 2 -->
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-800">AVO Meter Sanwa</td>
                                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">LAB-EL-012</td>
                                    <td class="px-6 py-4 text-slate-600">25 Jan 2026</td>
                                    <td class="px-6 py-4 text-slate-600 font-medium">27 Jan 2026</td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                            Dipakai
                                        </span>
                                    </td>
                                </tr>
                                <!-- Item 2 -->
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-800">AVO Meter Sanwa</td>
                                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">LAB-EL-012</td>
                                    <td class="px-6 py-4 text-slate-600">25 Jan 2026</td>
                                    <td class="px-6 py-4 text-slate-600 font-medium">27 Jan 2026</td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                            Dipakai
                                        </span>
                                    </td>
                                </tr>
                                <!-- Item 2 -->
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-800">AVO Meter Sanwa</td>
                                    <td class="px-6 py-4 text-slate-500 font-mono text-xs">LAB-EL-012</td>
                                    <td class="px-6 py-4 text-slate-600">25 Jan 2026</td>
                                    <td class="px-6 py-4 text-slate-600 font-medium">27 Jan 2026</td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">
                                            Dipakai
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 text-center">
                        <a href="#" class="text-xs font-medium text-blue-600 hover:text-blue-800">Lihat Riwayat
                            Lengkap &rarr;</a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Notifikasi & Quick Action -->
            <div class="lg:col-span-1 space-y-6">

                <!-- Notifikasi Terbaru -->
                <div class="bg-white rounded-xl shadow-sm border border-slate-200">
                    <div class="p-4 border-b border-slate-100">
                        <h3 class="font-semibold text-slate-800 text-sm">Notifikasi Terbaru</h3>
                    </div>
                    <div class="divide-y divide-slate-100 max-h-80 overflow-y-auto">
                        <!-- Notif 1 -->
                        <div class="p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex gap-3">
                                <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-blue-500"></div>
                                <div>
                                    <p class="text-sm text-slate-700">Permohonan peminjaman <span
                                            class="font-semibold">Solder Uap</span> telah disetujui Admin.</p>
                                    <p class="text-xs text-slate-400 mt-1">10 Menit yang lalu</p>
                                </div>
                            </div>
                        </div>
                        <!-- Notif 2 -->
                        <div class="p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex gap-3">
                                <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-gray-300"></div>
                                <div>
                                    <p class="text-sm text-slate-700">Jangan lupa mengembalikan <span
                                            class="font-semibold">Oscilloscope</span> sebelum tanggal 27 Jan.</p>
                                    <p class="text-xs text-slate-400 mt-1">1 Jam yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banner Lapor Masalah -->
                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-lg p-5 text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="font-bold text-lg mb-1">Temukan Kendala?</h3>
                        <p class="text-xs text-slate-300 mb-4 leading-relaxed">Laporkan kerusakan alat atau fasilitas
                            laboratorium agar segera diperbaiki.</p>
                        <button
                            class="w-full bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 rounded shadow-lg transition-colors">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> Buat Laporan
                        </button>
                    </div>
                    <i
                        class="fa-solid fa-screwdriver-wrench absolute -bottom-2 -right-2 text-6xl text-white opacity-10 rotate-45"></i>
                </div>
            </div>
        </div>
    </main>
@endsection
