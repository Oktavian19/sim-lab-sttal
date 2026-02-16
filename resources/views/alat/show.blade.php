<div class="relative w-full max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b bg-gray-50">
        <h5 class="text-lg font-semibold text-gray-800">Detail Alat</h5>
        <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="hideModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-1">
                <div class="border rounded-lg p-2 shadow-sm bg-gray-50">
                    @if ($alat->foto_alat && Storage::disk('public')->exists($alat->foto_alat))
                        <img src="{{ asset('storage/' . $alat->foto_alat) }}" alt="{{ $alat->nama_alat }}"
                            class="w-full h-auto rounded-md object-cover shadow-sm hover:scale-105 transition-transform duration-300">
                    @else
                        <div
                            class="w-full aspect-square flex flex-col items-center justify-center bg-gray-200 rounded-md text-gray-400">
                            <i class="fa-solid fa-image text-4xl mb-2"></i>
                            <span class="text-sm">Tidak ada foto</span>
                        </div>
                    @endif
                </div>

                <div class="mt-4 space-y-2">
                    <div class="text-center">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Kondisi</span>
                        <div class="mt-1">
                            @if ($alat->kondisi == 'baik')
                                <span
                                    class="px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full border border-green-200">
                                    Baik
                                </span>
                            @elseif($alat->kondisi == 'rusak_ringan')
                                <span
                                    class="px-3 py-1 text-sm font-medium text-yellow-800 bg-yellow-100 rounded-full border border-yellow-200">
                                    Rusak Ringan
                                </span>
                            @else
                                <span
                                    class="px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full border border-red-200">
                                    Rusak Berat
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-4">
                <h3 class="text-2xl font-bold text-gray-800 border-b pb-2 mb-4">{{ $alat->nama_alat }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 font-medium">Laboratorium (Lokasi)</p>
                        <p class="text-gray-900 text-base font-semibold">
                            <i class="fa-solid fa-location-dot text-red-500 mr-1"></i>
                            {{ $alat->laboratorium ? $alat->laboratorium->nama_lab : '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500 font-medium">Merk / Brand</p>
                        <p class="text-gray-900 text-base">{{ $alat->merk ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 font-medium">Tahun Pengadaan</p>
                        <p class="text-gray-900 text-base">{{ $alat->tahun_pengadaan ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500 font-medium">Terakhir Diupdate</p>
                        <p class="text-gray-900 text-base">{{ $alat->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <p class="text-blue-800 text-sm font-medium mb-1">Info Sistem:</p>
                    <ul class="list-disc list-inside text-xs text-blue-600">
                        <li>Dibuat pada: {{ $alat->created_at->format('d M Y') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end px-6 py-4 bg-gray-50 border-t gap-2">
        <button type="button"
            class="rounded-md bg-gray-500 px-4 py-2 text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-all"
            onclick="hideModal()">
            Tutup
        </button>
    </div>
</div>
