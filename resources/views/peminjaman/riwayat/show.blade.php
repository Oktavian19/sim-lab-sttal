<div class="relative w-full max-w-4xl mx-auto bg-white rounded-lg shadow-lg flex flex-col max-h-[90vh]">

    <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50 shrink-0">
        <h5 class="text-lg font-semibold text-gray-800">Detail Peminjaman</h5>
        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="hideModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <div class="p-6 space-y-6 overflow-y-auto flex-1 custom-scrollbar">

        <div class="bg-blue-50 rounded-lg border border-blue-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
                <div class="md:col-span-2">
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Peminjam</p>
                    <p class="text-gray-900 font-medium mt-1">{{ $peminjaman->peminjam->nama }}</p>
                    <p class="text-gray-600 text-xs">NRP. {{ $peminjaman->peminjam->nrp }}</p>
                </div>
                <div class="md:col-span-4">
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Kegiatan</p>
                    <p class="text-gray-900 font-medium mt-1">{{ $peminjaman->kegiatan }}</p>
                </div>
                <div class="md:col-span-3">
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Waktu Peminjaman</p>
                    <p class="text-blue-700 font-medium mt-1">
                        <i class="fa-solid fa-arrow-up mr-1"></i>
                        {{ $peminjaman->start_time->translatedFormat('d F Y') }}
                    </p>
                    <p class="text-gray-600 text-s">{{ $peminjaman->start_time->format('H:i') }} WIB</p>
                </div>
                <div class="md:col-span-3">
                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Waktu Pengembalian</p>
                    <p class="text-red-600 font-medium mt-1">
                        <i class="fa-solid fa-arrow-down mr-1"></i>
                        {{ $peminjaman->end_time->translatedFormat('d F Y') }}
                    </p>
                    <p class="text-gray-600 text-s">{{ $peminjaman->end_time->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">
                Laboratorium Diajukan
            </h3>

            @if ($peminjaman->laboratorium)
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <i class="fa-solid fa-building-columns text-lg"></i>
                        </div>
                    </div>

                    <div class="flex-1">
                        <p class="text-gray-900 font-semibold text-base leading-tight">
                            {{ $peminjaman->laboratorium->nama_lab }}
                        </p>

                        <div class="mt-1.5 flex items-center">
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-gray-50 text-gray-700 border border-gray-100">
                                <i class="fa-solid fa-building text-[10px]"></i>
                                Kapasitas: {{ $peminjaman->laboratorium->kapasitas }} Orang
                            </span>
                            <span
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                <i class="fa-solid fa-users text-[10px]"></i>
                                Peserta: {{ $peminjaman->jumlah_peserta }} Orang
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="flex flex-col items-center justify-center py-8 border-2 border-dashed border-gray-200 rounded-lg bg-gray-50">
                    <div class="p-3 bg-white rounded-full shadow-sm mb-3">
                        <i class="fa-solid fa-box-open text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium text-sm">Tidak ada laboratorium yang diajukan</p>
                    <p class="text-gray-400 text-xs mt-1">Peminjaman ini hanya untuk alat</p>
                </div>
            @endif
        </div>

        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">Daftar Alat Diajukan</h3>

            @if ($detailAlat->isNotEmpty())
                <div class="overflow-hidden border border-gray-200 rounded-lg">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 font-semibold border-b">
                            <tr>
                                <th class="px-4 py-3">Nama Alat</th>
                                <th class="px-4 py-3">Merek</th>
                                <th class="px-4 py-3">Lokasi (Lab)</th>
                                <th class="px-4 py-3">Jumlah Pinjam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($detailAlat as $detail)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $detail->alat->nama_alat }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $detail->alat->merk }}
                                    </td>
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $detail->alat->laboratorium->nama_lab }}
                                    </td>
                                    <td class="px-4 py-3">
                                        {{ $detail->jumlah }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div
                    class="flex flex-col items-center justify-center py-8 border-2 border-dashed border-gray-200 rounded-lg bg-gray-50">
                    <div class="p-3 bg-white rounded-full shadow-sm mb-3">
                        <i class="fa-solid fa-box-open text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 font-medium text-sm">Tidak ada alat yang diajukan</p>
                    <p class="text-gray-400 text-xs mt-1">Peminjaman ini hanya untuk ruangan</p>
                </div>
            @endif
        </div>

        <div>
            <label for="catatan_admin" class="block text-sm font-medium text-gray-700 mb-1">
                Catatan Admin
            </label>
            <div rows="3"
                class="w-full rounded-md bg-gray-100 border-gray-300 shadow-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 border">
                {{ $peminjaman->catatan_admin ?? 'Tidak ada catatan' }}
            </div>
        </div>

        {{-- BAGIAN BUKTI PENGAMBILAN (Menyusul jika status >= dipinjam) --}}
        @if ($peminjaman->bukti_pengambilan)
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2">Bukti Pengambilan (Awal)</h3>
                <div
                    class="mt-2 rounded-lg border border-gray-200 bg-gray-50 p-2 flex flex-col items-center justify-center">
                    <a href="{{ asset('storage/' . $peminjaman->bukti_pengambilan) }}" target="_blank"
                        class="block w-full text-center">
                        <img src="{{ asset('storage/' . $peminjaman->bukti_pengambilan) }}" alt="Bukti Pengambilan"
                            class="max-h-64 object-contain mx-auto rounded-md shadow-sm hover:opacity-90 transition-opacity cursor-pointer border border-gray-300">
                    </a>
                </div>
            </div>
        @endif

        {{-- BAGIAN PENGEMBALIAN --}}
        @if ($peminjaman->pengembalian)
            <div>
                <h3 class="text-lg font-bold text-gray-800 mb-3 border-b pb-2 flex items-center gap-2">
                    Informasi Pengembalian
                    @if ($peminjaman->pengembalian->status_pengembalian === 'terlambat')
                        <span
                            class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 uppercase border border-red-200">Terlambat</span>
                    @endif
                </h3>

                @php
                    $isLate = $peminjaman->pengembalian->status_pengembalian === 'terlambat';
                    $theme = $isLate
                        ? [
                            'bg' => 'bg-red-50',
                            'border' => 'border-red-200',
                            'text' => 'text-red-700',
                            'icon' => 'fa-circle-exclamation',
                            'icon_color' => 'text-red-500',
                        ]
                        : [
                            'bg' => 'bg-emerald-50',
                            'border' => 'border-emerald-200',
                            'text' => 'text-emerald-700',
                            'icon' => 'fa-circle-check',
                            'icon_color' => 'text-emerald-500',
                        ];
                @endphp

                <div class="rounded-xl border {{ $theme['border'] }} {{ $theme['bg'] }} overflow-hidden mb-6">
                    <div class="p-5">
                        <div class="flex flex-col md:flex-row gap-6">

                            {{-- Kolom Kiri: Ikon Besar & Status Utama --}}
                            <div
                                class="flex-shrink-0 flex md:flex-col items-center md:justify-center gap-3 md:w-24 md:border-r {{ $isLate ? 'border-red-200' : 'border-emerald-200' }} md:pr-4">
                                <i class="fa-solid {{ $theme['icon'] }} text-4xl {{ $theme['icon_color'] }}"></i>
                                <div class="text-center">
                                    <span
                                        class="block text-xs font-bold uppercase tracking-wider {{ $theme['text'] }}">Status</span>
                                    <span class="block font-semibold text-sm text-gray-700 mt-0.5">
                                        {{ $isLate ? 'Terlambat' : 'Selesai' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Detail Grid --}}
                            <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">

                                {{-- Waktu Kembali --}}
                                <div>
                                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Waktu
                                        Dikembalikan</p>
                                    <p class="text-gray-900 font-medium mt-1 text-base">
                                        {{ $peminjaman->pengembalian->tanggal_kembali_realisasi->translatedFormat('d F Y') }}
                                    </p>
                                    <p class="text-gray-500 text-sm flex items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-xs"></i>
                                        {{ $peminjaman->pengembalian->tanggal_kembali_realisasi->format('H:i') }} WIB
                                    </p>
                                </div>

                                {{-- Petugas Verifikator --}}
                                <div>
                                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Diverifikasi
                                        Oleh</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <div
                                            class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs">
                                            <i class="fa-solid fa-user"></i>
                                        </div>
                                        <p class="text-gray-900 font-medium text-sm">
                                            {{ $peminjaman->pengembalian->petugas->nama ?? 'Admin / Petugas' }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Catatan --}}
                                <div class="md:col-span-2">
                                    <p class="text-gray-500 text-xs font-semibold uppercase tracking-wider">Catatan
                                        Pengembalian</p>
                                    @if ($peminjaman->pengembalian->catatan)
                                        <p
                                            class="text-gray-700 text-sm mt-1 bg-white/60 p-2 rounded border {{ $isLate ? 'border-red-100' : 'border-emerald-100' }}">
                                            "{{ $peminjaman->pengembalian->catatan }}"
                                        </p>
                                    @else
                                        <p class="text-gray-400 text-sm italic mt-1">- Tidak ada catatan -</p>
                                    @endif
                                </div>

                                {{-- Denda (Hanya muncul jika ada) --}}
                                @if ($peminjaman->pengembalian->denda_atau_sanksi)
                                    <div class="md:col-span-2 mt-1">
                                        <div
                                            class="rounded-md bg-red-100 border border-red-200 p-3 flex items-start gap-3">
                                            <i class="fa-solid fa-triangle-exclamation text-red-600 mt-0.5"></i>
                                            <div>
                                                <p class="text-xs font-bold text-red-800 uppercase tracking-wide">Denda
                                                    / Sanksi Diterapkan</p>
                                                <p class="text-sm text-red-700 mt-1 font-medium">
                                                    {{ $peminjaman->pengembalian->denda_atau_sanksi }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

                <h4 class="text-sm font-bold text-gray-800 mb-2 mt-4 border-b pb-1">Lampiran Foto Bukti Pengembalian
                </h4>
                @if ($peminjaman->bukti_pengembalian)
                    <div
                        class="mt-2 rounded-lg border border-gray-200 bg-gray-50 p-2 flex flex-col items-center justify-center">
                        <a href="{{ asset('storage/' . $peminjaman->bukti_pengembalian) }}" target="_blank"
                            class="block w-full text-center">
                            <img src="{{ asset('storage/' . $peminjaman->bukti_pengembalian) }}"
                                alt="Bukti Pengembalian"
                                class="max-h-64 object-contain mx-auto rounded-md shadow-sm hover:opacity-90 transition-opacity cursor-pointer border border-gray-300">
                        </a>
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fa-solid fa-magnifying-glass-plus mr-1"></i> Klik gambar untuk ukuran penuh
                        </p>
                    </div>
                @else
                    <div
                        class="flex flex-col items-center justify-center py-6 border-2 border-dashed border-gray-200 rounded-lg bg-gray-50">
                        <i class="fa-solid fa-image text-2xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500 font-medium text-sm">Bukti foto pengembalian tidak diunggah</p>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
