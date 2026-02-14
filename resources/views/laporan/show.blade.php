<div class="relative w-full max-w-2xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden">
    <div class="absolute top-4 right-4 z-10">
        <button type="button" class="bg-black/50 hover:bg-black/70 text-white rounded-full p-2 transition-colors" onclick="hideModal()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <div class="p-4">
        <div class="relative group">
            @if ($laporan->foto_bukti && Storage::disk('public')->exists($laporan->foto_bukti))
                <img src="{{ asset('storage/' . $laporan->foto_bukti) }}" 
                     alt="{{ $laporan->alat->nama_alat }}"
                     class="w-full h-auto max-h-[70vh] rounded-lg object-contain bg-gray-100">
            @else
                <div class="w-full aspect-video flex flex-col items-center justify-center bg-gray-100 rounded-lg text-gray-400">
                    <i class="fa-solid fa-image text-6xl mb-3"></i>
                    <span class="text-lg font-medium">Foto Tidak Tersedia</span>
                </div>
            @endif
            
            <div class="mt-4 text-center">
                <h3 class="text-xl font-bold text-gray-800">{{ $laporan->alat->nama_alat }}</h3>
                <p class="text-sm text-gray-500">{{ $laporan->alat->merk ?? 'Tanpa Merk' }}</p>
            </div>
        </div>
    </div>
</div>