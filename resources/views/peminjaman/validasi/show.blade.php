<div class="relative w-full max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">

    <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
        <h5 class="text-lg font-semibold text-gray-800">Validasi Peminjaman</h5>
        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="hideModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <form form action="" method="POST" id="formValidasi" class="validate">
        @csrf
        @method('PATCH')

        <div class="p-6 space-y-6">
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
                                    <th class="px-4 py-3 text-center">Kondisi Saat Ini</th>
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
                                        <td class="px-4 py-3 text-center">
                                            @php
                                                $badge = match ($detail->alat->status_kondisi) {
                                                    'baik' => ['color' => 'green', 'label' => 'Baik'],
                                                    'rusak_ringan' => ['color' => 'yellow', 'label' => 'Rusak Ringan'],
                                                    'rusak_berat' => ['color' => 'red', 'label' => 'Rusak Berat'],
                                                    default => ['color' => 'gray', 'label' => '-'],
                                                };
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-800">
                                                {{ $badge['label'] }}
                                            </span>
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
                    Catatan Admin / Alasan Penolakan <span class="text-red-500 text-xs hidden" id="req-label">*Wajib jika ditolak</span>
                </label>
                <textarea name="catatan_admin" id="catatan_admin" rows="3"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-3 border"
                    placeholder="Berikan catatan opsional jika disetujui, atau alasan wajib jika ditolak..."></textarea>
            </div>
        </div>

        <div class="flex justify-end items-center px-6 py-4 bg-gray-50 border-t">
            <div class="flex gap-3">
                <button type="button"
                    onclick="submitValidation('{{ route('peminjaman.reject', $peminjaman->id) }}', 'reject')"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-100 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all">
                    <i class="fa-solid fa-xmark mr-2"></i> Tolak
                </button>

                <button type="button"
                onclick="submitValidation('{{ route('peminjaman.approve', $peminjaman->id) }}', 'approve')"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                    <i class="fa-solid fa-check mr-2"></i> Setujui Peminjaman
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function submitValidation(url, type) {
        let form = $('#formValidasi');
        let catatan = $('#catatan_admin').val();

        if (type === 'reject' && !catatan.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Mohon isi alasan penolakan pada kolom catatan admin.',
            });
            $('#catatan_admin').focus();
            $('#req-label').removeClass('hidden');
            return;
        }

        form.attr('action', url);

        form.submit();
    }
</script>