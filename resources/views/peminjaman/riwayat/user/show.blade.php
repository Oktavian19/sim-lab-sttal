<div
    class="bg-white rounded-2xl shadow-xl w-full max-w-2xl relative z-10 overflow-hidden flex flex-col max-h-[90vh] transform transition-all animate-fade-in-up">

    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
        <div>
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                Detail Peminjaman <span class="text-gray-400 font-normal">#{{ $peminjaman->id }}</span>
            </h3>
            <p class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($peminjaman->start_time)->translatedFormat('l, d F Y') }}</p>
        </div>
        <button onclick="hideModal()" class="p-2 rounded-full hover:bg-gray-200 transition-colors">
            <i class="fa-solid fa-xmark text-gray-500 text-lg"></i>
        </button>
    </div>

    <div class="p-6 overflow-y-auto">

        <div
            class="mb-6 flex flex-col sm:flex-row justify-between bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
            <div>
                <p class="text-sm text-gray-500 mb-1">Status Pengajuan</p>
                @php
                    $badges = [
                        'pending' => [
                            'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'label' => 'Menunggu Konfirmasi',
                        ],
                        'disetujui' => [
                            'class' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'label' => 'Disetujui (Aktif)',
                        ],
                        'dipinjam' => [
                            'class' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'label' => 'Dipinjam',
                        ],
                        'selesai' => ['class' => 'bg-green-100 text-green-800 border-green-200', 'label' => 'Selesai'],
                        'ditolak' => ['class' => 'bg-red-100 text-red-800 border-red-200', 'label' => 'Ditolak'],
                        'dibatalkan' => [
                            'class' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'label' => 'Dibatalkan',
                        ],
                    ];
                    $currentBadge = $badges[$peminjaman->status_pengajuan] ?? $badges['pending'];
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $currentBadge['class'] }}">
                    {{ $currentBadge['label'] }}
                </span>
            </div>

            @if ($peminjaman->catatan_admin)
                <div class="mt-3 sm:mt-0 sm:text-right max-w-xs">
                    <p class="text-sm text-gray-500 mb-1">Catatan Admin</p>
                    <p class="text-sm text-gray-800 italic">"{{ $peminjaman->catatan_admin }}"</p>
                </div>
            @endif
        </div>

        @if ($peminjaman->laboratorium)
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-location-dot"></i> Informasi Ruangan
                </h4>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="font-medium text-gray-900">{{ $peminjaman->laboratorium->nama_lab }}</span>
                        <span class="text-xs bg-white px-2 py-1 rounded border border-gray-300 text-gray-600">
                            Kapasitas: {{ $peminjaman->laboratorium->kapasitas }} Orang
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <div class="mb-6">
            <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                <i class="fa-solid fa-box"></i> Daftar Alat Dipinjam
            </h4>

            @if ($peminjaman->detailAlat->count() > 0)
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 font-medium">Nama Alat</th>
                                <th class="px-4 py-3 font-medium">Lokasi</th>
                                <th class="px-4 py-3 font-medium">Jumlah</th>
                                @if ($peminjaman->status_pengajuan === 'dipinjam')
                                <th class="px-4 py-3 font-medium">Kondisi Saat Peminjaman</th>
                                @endif
                                @if ($peminjaman->status_pengajuan === 'selesai')
                                    <th class="px-4 py-3 font-medium">Kondisi Saat Pengembalian</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($peminjaman->detailAlat as $detail)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900">{{ $detail->alat->nama_alat }}</div>
                                        <div class="text-xs text-gray-500">{{ $detail->alat->merk }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="font-medium text-gray-900">
                                            {{ $detail->alat->laboratorium->nama_lab }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 bg-green-50 text-green-700 rounded text-xs border border-green-100">
                                            {{ $detail->jumlah }}
                                        </span>
                                    </td>
                                    @if ($peminjaman->status_pengajuan === 'selesai')
                                        <td class="px-4 py-3">
                                            @php
                                                $isRusak = $detail->kondisi_saat_kembali === 'rusak';
                                                $bgClass = $isRusak
                                                    ? 'bg-red-50 text-red-700 border-red-100'
                                                    : 'bg-green-50 text-green-700 border-green-100';
                                            @endphp
                                            <span class="px-2 py-1 rounded text-xs border {{ $bgClass }}">
                                                {{ $detail->kondisi_saat_kembali ?? '-' }}
                                            </span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500 italic">Tidak ada alat tambahan yang dipinjam.</p>
            @endif
        </div>

        @if ($peminjaman->pengembalian)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-green-600"></i> Informasi Pengembalian
                </h4>

                @php
                    $isLate = $peminjaman->pengembalian->status_pengembalian === 'terlambat';
                    $boxClass = $isLate ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200';
                    $textClass = $isLate ? 'text-red-700' : 'text-green-700';
                @endphp

                <div class="rounded-lg p-4 border {{ $boxClass }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs">Waktu Dikembalikan</p>
                            <p class="font-medium text-gray-900">
                                {{ \Carbon\Carbon::parse($peminjaman->pengembalian->tanggal_kembali_realisasi)->translatedFormat('d F Y • H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs">Status</p>
                            <p class="font-bold capitalize {{ $textClass }}">
                                {{ str_replace('_', ' ', $peminjaman->pengembalian->status_pengembalian) }}
                            </p>
                        </div>

                        @if ($peminjaman->pengembalian->denda_atau_sanksi)
                            <div
                                class="col-span-1 sm:col-span-2 bg-white bg-opacity-60 p-2 rounded border border-red-100">
                                <p class="text-red-600 text-xs font-bold flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i> Denda / Sanksi
                                </p>
                                <p class="text-red-800 font-medium">{{ $peminjaman->pengembalian->denda_atau_sanksi }}
                                </p>
                            </div>
                        @endif

                        @if ($peminjaman->pengembalian->catatan)
                            <div class="col-span-1 sm:col-span-2">
                                <p class="text-gray-500 text-xs">Catatan Petugas</p>
                                <p class="text-gray-800 italic">"{{ $peminjaman->pengembalian->catatan }}"</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        @if ($peminjaman->status_pengajuan === 'pending')
            <div class="mt-4 flex justify-end">
                <form action="{{ route('peminjaman.cancel', $peminjaman->id) }}" method="POST"
                    onsubmit="return confirmCancel(event)">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors">
                        Batalkan Pengajuan
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
