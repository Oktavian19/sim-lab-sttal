@extends('layouts.app')

@section('title', 'Dashboard - SIM LAB STTAL')
@section('header', 'Dashboard')

@section('content')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div
            class="bg-gradient-to-r from-blue-800 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-2xl font-bold mb-2">Selamat Siang, {{ auth()->user()->pangkat }} {{ auth()->user()->nama }}!
                </h3>
                <p class="text-blue-100 max-w-2xl">
                    Selamat datang di SIM LAB STTAL. Anda dapat memantau jadwal praktikum, meminjam alat, dan melaporkan
                    kendala fasilitas di sini.
                </p>
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('peminjaman.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md shadow-blue-200 transition-all">
                        <i class="fa-solid fa-plus mr-2"></i> Pinjam Alat Baru
                    </a>
                    <a href="{{ route('peminjaman.schedule') }}"
                        class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium transition-all">
                        <i class="fa-solid fa-calendar-check mr-2"></i> Cek Jadwal Lab
                    </a>
                </div>
            </div>
            <div class="absolute right-0 bottom-0 opacity-5 pointer-events-none md:block hidden">
                <i class="fa-solid fa-microscope text-[180px] text-blue-900 -mr-10 -mb-10"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            <div
                class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pinjaman Aktif</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $countPinjamanAktif ?? 0 }} <span
                                class="text-sm font-normal text-slate-500">Pengajuan</span></h3>
                    </div>
                    <div class="bg-blue-50 p-2 rounded-lg text-blue-600">
                        <i class="fa-solid fa-hand-holding-hand text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500"><i class="fa-solid fa-info-circle mr-1"></i> Sedang dipinjam</span>
                    <a href="{{ route('peminjaman.riwayatUser') }}" class="text-xs text-blue-600 hover:underline">Lihat
                        Detail</a>
                </div>
            </div>

            <div
                class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-400 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Menunggu Persetujuan</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1">{{ $countPending ?? 0 }} <span
                                class="text-sm font-normal text-slate-500">Request</span></h3>
                    </div>
                    <div class="bg-yellow-50 p-2 rounded-lg text-yellow-600">
                        <i class="fa-solid fa-clock text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t border-slate-100">
                    <p class="text-xs text-slate-500">Pengajuan berstatus pending</p>
                </div>
            </div>

            <div
                class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Status Akun</p>
                        <h3 class="text-lg font-bold text-green-600 mt-2">AKTIF</h3>
                    </div>
                    <div class="bg-green-50 p-2 rounded-lg text-green-600">
                        <i class="fa-solid fa-user-check text-xl"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-t border-slate-100">
                    <p class="text-xs text-slate-500">Tidak ada denda atau sanksi.</p>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6 flex flex-col h-[50vh]">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full">
                    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50 shrink-0">
                        <h3 class="font-semibold text-slate-800"><i
                                class="fa-solid fa-list-check text-blue-500 mr-2"></i>Pinjaman Saya Saat Ini</h3>
                        <span
                            class="text-xs font-medium px-2.5 py-0.5 rounded bg-blue-100 text-blue-800">{{ $pinjamanAktif->count() ?? 0 }}
                            Data</span>
                    </div>
                    <div class="overflow-y-auto flex-1 relative">
                        <table class="w-full text-sm text-left text-slate-500">
                            <thead class="text-xs text-slate-700 uppercase bg-slate-50 sticky top-0 shadow-sm z-10">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Kegiatan / Lab</th>
                                    <th scope="col" class="px-6 py-3">Waktu Pinjam</th>
                                    <th scope="col" class="px-6 py-3">Tenggat Waktu</th>
                                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($pinjamanAktif as $item)
                                    <tr class="bg-white border-b hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-800">{{ $item->kegiatan }}</div>
                                            <div class="text-xs text-slate-400">
                                                {{ $item->laboratorium->nama_lab ?? 'Hanya Peminjaman Alat' }}</div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-slate-600">{{ $item->start_time->translatedFormat('d M Y') }}
                                            </div>
                                            <div class="text-xs text-slate-400">{{ $item->start_time->format('H:i') }} WIB
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            @php
                                                $isOverdue = $item->end_time->isPast();
                                            @endphp
                                            <div
                                                class="{{ $isOverdue ? 'text-red-600 font-semibold' : 'text-slate-600' }}">
                                                {{ $item->end_time->translatedFormat('d M Y') }}
                                            </div>
                                            <div class="text-xs {{ $isOverdue ? 'text-red-500' : 'text-slate-400' }}">
                                                {{ $item->end_time->format('H:i') }} WIB
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if ($item->status_pengajuan == 'dipinjam')
                                                <span
                                                    class="px-2.5 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                                                    Dipinjam
                                                </span>
                                            @elseif($item->status_pengajuan == 'disetujui')
                                                <span
                                                    class="px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700 border border-green-200">
                                                    Disetujui
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="bg-slate-50 p-4 rounded-full mb-3">
                                                    <i class="fa-solid fa-box-open text-4xl text-slate-300"></i>
                                                </div>
                                                <span class="text-slate-600 font-medium text-base">Tidak ada pinjaman
                                                    aktif</span>
                                                <span class="text-slate-400 text-xs mt-1">Anda belum memiliki peminjaman
                                                    yang sedang berjalan.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-3 bg-slate-50 border-t border-slate-100 text-center shrink-0">
                        <a href="{{ route('peminjaman.riwayatUser') }}"
                            class="text-xs font-medium text-blue-600 hover:text-blue-800">Lihat Riwayat Lengkap &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 flex flex-col h-[50vh] space-y-6">

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col flex-1 min-h-0">
                    <div class="p-4 border-b border-slate-100 flex justify-between items-center shrink-0">
                        <h3 class="font-semibold text-slate-800 text-sm">Update Peminjaman</h3>
                    </div>
                    <div class="divide-y divide-slate-100 overflow-y-auto flex-1">
                        @forelse($notifikasi as $notif)
                            <div class="p-4 hover:bg-slate-50 transition-colors">
                                <div class="flex gap-3">
                                    @if ($notif->status_pengajuan == 'disetujui')
                                        <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-green-500"></div>
                                        <div>
                                            <p class="text-sm text-slate-700">Peminjaman <span
                                                    class="font-semibold">{{ $notif->kegiatan }}</span> telah disetujui.
                                            </p>
                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $notif->updated_at->diffForHumans() }}</p>
                                        </div>
                                    @elseif($notif->status_pengajuan == 'ditolak')
                                        <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-red-500"></div>
                                        <div>
                                            <p class="text-sm text-slate-700">Peminjaman <span
                                                    class="font-semibold">{{ $notif->kegiatan }}</span> ditolak oleh admin.
                                            </p>
                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $notif->updated_at->diffForHumans() }}</p>
                                        </div>
                                    @elseif($notif->status_pengajuan == 'selesai')
                                        <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-blue-500"></div>
                                        <div>
                                            <p class="text-sm text-slate-700">Peminjaman <span
                                                    class="font-semibold">{{ $notif->kegiatan }}</span> telah selesai
                                                dikembalikan.</p>
                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $notif->updated_at->diffForHumans() }}</p>
                                        </div>
                                    @else
                                        <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-yellow-500"></div>
                                        <div>
                                            <p class="text-sm text-slate-700">Pengajuan <span
                                                    class="font-semibold">{{ $notif->kegiatan }}</span> sedang diproses
                                                (Pending).</p>
                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $notif->created_at->diffForHumans() }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-slate-500 text-sm h-full flex flex-col justify-center">
                                <div>
                                    <i class="fa-regular fa-bell-slash text-2xl text-slate-300 mb-2 block"></i>
                                    Belum ada notifikasi terbaru.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-lg p-5 text-white relative overflow-hidden shrink-0">
                    <div class="relative z-10">
                        <h3 class="font-bold text-lg mb-1">Temukan Kendala?</h3>
                        <p class="text-xs text-slate-300 mb-4 leading-relaxed">Laporkan kerusakan alat atau fasilitas
                            laboratorium agar segera diperbaiki.</p>
                        <a href="{{ route('laporan.create') }}"
                            class="block text-center w-full bg-red-600 hover:bg-red-700 text-white text-sm font-medium py-2 rounded shadow-lg transition-colors">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> Buat Laporan
                        </a>
                    </div>
                    <i
                        class="fa-solid fa-screwdriver-wrench absolute -bottom-2 -right-2 text-6xl text-white opacity-10 rotate-45"></i>
                </div>
            </div>
        </div>
    </main>
@endsection