@extends('layouts.app')

@section('title', 'Dashboard Admin - SIM LAB STTAL Admin')
@section('header', 'Dashboard Admin')

@section('content')
    <main class="flex-1 flex flex-col overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
        <div
            class="shrink-0 bg-gradient-to-r from-blue-800 to-blue-600 rounded-lg shadow-lg p-6 mb-8 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-2xl font-bold mb-2">Selamat Datang, Admin!</h3>
                <p class="text-blue-100 max-w-2xl">Sistem Informasi Administrasi Laboratorium STTAL siap digunakan. Anda
                    memiliki <span class="font-bold text-yellow-300">{{ $peminjaman['validasi'] }} permohonan
                        peminjaman</span> yang menunggu
                    validasi.</p>
            </div>
            <i class="fa-solid fa-flask absolute -bottom-4 -right-4 text-9xl text-white opacity-10 rotate-12"></i>
        </div>

        <div class="shrink-0 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Total Alat</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $alat['total'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fa-solid fa-microscope"></i>
                    </div>
                </div>
                <div class="text-xs text-slate-500">
                    <span class="text-green-500 font-bold"><i class="fa-solid fa-check"></i>
                        {{ $alat['sedang_dipinjam'] }}</span> sedang dipinjam
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Menunggu Validasi</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $peminjaman['validasi'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                </div>
                <div class="text-xs text-slate-500">
                    <a href="{{ route('peminjaman.validasi') }}" class="text-blue-600 hover:underline">Lihat Pengajuan
                        &rarr;</a>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-green-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Lab Terpakai</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $lab['used'] }} <span
                                class="text-sm font-normal text-slate-400">/
                                {{ $lab['total'] }}</span></p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <i class="fa-solid fa-door-open"></i>
                    </div>
                </div>
                <div class="text-xs text-slate-500">
                    Lab Elektronika & Lab Komputer
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-red-500 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Laporan Kerusakan</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $lapKerusakan['total'] }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                </div>

                <div class="text-xs text-slate-500">
                    @if ($lapKerusakan['hari_ini'] > 0)
                        <span class="text-red-500 font-bold">{{ $lapKerusakan['hari_ini'] }}</span> Baru masuk hari ini
                    @else
                        Tidak ada laporan baru hari ini
                    @endif
                </div>
            </div>
        </div>

        <div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-slate-200 flex flex-col h-[50vh]">
                <div class="p-4 border-b border-slate-100 flex justify-between items-center shrink-0">
                    <h3 class="font-bold text-slate-800">
                        <i class="fa-solid fa-list-check mr-2 text-blue-500"></i>Peminjaman Perlu Persetujuan
                    </h3>
                    <a href="{{ route('peminjaman.validasi') }}"
                        class="text-xs font-medium text-blue-600 hover:text-blue-800">Lihat Semua</a>
                </div>

                <div class="overflow-y-auto flex-1 relative">
                    <table class="w-full text-sm text-left text-slate-500">
                        <thead class="text-xs text-slate-700 uppercase bg-slate-50 sticky top-0 shadow-sm z-10">
                            <tr>
                                <th scope="col" class="px-6 py-3">Peminjam (User)</th>
                                <th scope="col" class="px-6 py-3">Kegiatan</th>
                                <th scope="col" class="px-6 py-3">Laboratorium</th>
                                <th scope="col" class="px-6 py-3">Waktu Pinjam</th>
                                <th scope="col" class="px-6 py-3 text-center">Jumlah Peserta</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($peminjaman['validasiData'] as $item)
                                <tr class="bg-white border-b hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $item->peminjam->nama }}</div>
                                        <div class="text-xs text-slate-400">NRP: {{ $item->peminjam->nrp }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $item->kegiatan }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">{{ $item->laboratorium->nama_lab ?? 'Hanya peminjaman alat' }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-900">
                                            <i class="fa-regular fa-calendar mr-1"></i>
                                            {{ $item->start_time->translatedFormat('d F Y') }}
                                        </div>

                                        <div class="text-xs text-slate-400">
                                            @if ($item->start_time->diffInHours($item->end_time) < 24)
                                                {{ $item->start_time->format('H:i') }} -
                                                {{ $item->end_time->format('H:i') }}
                                            @else
                                                {{ (int) $item->start_time->diffInDays($item->end_time) }} Hari
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">{{ $item->jumlah_peserta }} Orang</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-slate-50 p-4 rounded-full mb-3">
                                                <i class="fa-solid fa-clipboard-check text-4xl text-slate-300"></i>
                                            </div>
                                            <span class="text-slate-600 font-medium text-base">Tidak ada antrean
                                                validasi</span>
                                            <span class="text-slate-400 text-xs mt-1">Semua pengajuan peminjaman saat ini
                                                telah diproses.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="lg:col-span-1 flex flex-col space-y-6 h-[50vh]">

                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4 flex-1 flex flex-col overflow-hidden">
                    <h3 class="font-bold text-slate-800 mb-4 text-sm shrink-0">Status Laboratorium</h3>
                    <div class="space-y-3 overflow-y-auto flex-1 pr-2">
                        @foreach ($lab['labData'] as $item)
                            @php
                                $isSedangDipakai = in_array($item->id, $lab['active_lab_ids']);

                                if ($item->status === 'nonaktif') {
                                    $statusClass = 'bg-gray-50 border-gray-500';
                                    $badgeClass = 'text-gray-700 bg-gray-200';
                                    $statusText = 'Nonaktif';
                                } elseif ($item->status === 'perbaikan') {
                                    $statusClass = 'bg-yellow-50 border-yellow-500';
                                    $badgeClass = 'text-yellow-700 bg-yellow-200';
                                    $statusText = 'Perbaikan';
                                } elseif ($isSedangDipakai) {
                                    $statusClass = 'bg-red-50 border-red-500';
                                    $badgeClass = 'text-red-700 bg-red-200';
                                    $statusText = 'Sedang Digunakan';
                                } else {
                                    $statusClass = 'bg-green-50 border-green-500';
                                    $badgeClass = 'text-green-700 bg-green-200';
                                    $statusText = 'Tersedia';
                                }
                            @endphp

                            <div class="flex items-center justify-between p-2 rounded border-l-4 mb-2 {{ $statusClass }}">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">{{ $item->nama_lab }}</p>

                                    @if ($isSedangDipakai)
                                        <p class="text-xs text-slate-500">Kapasitas: {{ $item->kapasitas }}</p>
                                    @else
                                        <p class="text-xs text-slate-500">{{ $item->kegiatan }}</p>
                                    @endif
                                </div>

                                <span class="text-xs font-bold px-2 py-1 rounded {{ $badgeClass }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-slate-800 rounded-lg shadow-sm p-4 text-white shrink-0">
                    <h3 class="font-bold mb-3 text-sm">Aksi Cepat</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('alat.index', ['open_modal' => 'true']) }}"
                            class="flex flex-col items-center justify-center bg-slate-700 hover:bg-blue-600 p-3 rounded transition">
                            <i class="fa-solid fa-plus-circle text-xl mb-1"></i>
                            <span class="text-xs">Tambah Alat</span>
                        </a>
                        <a href="{{ route('user.index', ['role' => 'user', 'open_modal' => 'true']) }}"
                            class="flex flex-col items-center justify-center bg-slate-700 hover:bg-blue-600 p-3 rounded transition">
                            <i class="fa-solid fa-user-plus text-xl mb-1"></i>
                            <span class="text-xs">Tambah User</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
