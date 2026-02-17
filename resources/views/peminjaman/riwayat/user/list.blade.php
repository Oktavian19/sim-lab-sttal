@forelse($peminjaman as $item)
    <div onclick="modalAction('{{ route('peminjaman.riwayatUser.show', $item->id) }}')"
        class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-indigo-200 transition-all cursor-pointer group">

        <div class="p-5">
            <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4 mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs text-gray-500">Diajukan:
                            {{ $item->created_at->translatedFormat('d F Y') }}</span>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                        {{ $item->kegiatan }}
                    </h3>
                    <div class="flex items-center gap-2 mt-1 text-sm text-gray-600">
                        @if ($item->laboratorium)
                            <i class="fa-solid fa-location-dot w-4"></i> {{ $item->laboratorium->nama_lab }}
                        @else
                            <span class="italic text-gray-500">Hanya Peminjaman Alat</span>
                        @endif
                    </div>
                </div>
                @php
                    $statusClass = match ($item->status_pengajuan) {
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'disetujui' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'selesai' => 'bg-green-100 text-green-800 border-green-200',
                        'ditolak', 'dibatalkan' => 'bg-red-100 text-red-800 border-red-200',
                        default => 'bg-gray-100 text-gray-800 border-gray-200',
                    };
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                    {{ ucfirst($item->status_pengajuan) }}
                </span>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 pt-4 border-t border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                        <i class="fa-regular fa-calendar w-5 h-5 flex justify-center items-center"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tanggal</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($item->start_time)->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                        <i class="fa-regular fa-clock w-5 h-5 flex justify-center items-center"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Jam</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
        <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3 block"></i>
        <h3 class="text-lg font-medium text-gray-900">Tidak ada data ditemukan</h3>
        <p class="text-gray-500">Data tidak ditemukan sesuai pencarian.</p>
    </div>
@endforelse

<div class="mt-4">
    {{ $peminjaman->links() }}
</div>