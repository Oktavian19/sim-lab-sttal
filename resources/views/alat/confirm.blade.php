@empty($alat)
    <div id="modal-master" class="relative z-50 w-full max-w-lg mx-auto my-10" role="document">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h5 class="text-xl font-bold text-gray-800">Kesalahan</h5>
                <button type="button" onclick="hideModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 text-red-700">
                    <div class="flex items-center mb-2 font-bold uppercase tracking-wide text-sm">
                        <i class="fas fa-ban mr-2"></i>
                        <span>Kesalahan</span>
                    </div>
                    <p class="text-sm">Data alat yang anda cari tidak ditemukan.</p>
                </div>
                <a href="{{ url('alat') }}" class="inline-block px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('alat/' . $alat->id . '/destroy') }}" method="POST" id="form-delete" class="validate">
        @csrf
        <div id="modal-master" class="relative z-50 w-full max-w-2xl mx-auto my-10" role="document">
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h5 class="text-xl font-bold text-gray-800">Hapus Data Alat</h5>
                    <button type="button" onclick="hideModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="p-6">
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6 text-amber-800">
                        <div class="flex items-center mb-1 font-bold italic">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>Konfirmasi</span>
                        </div>
                        <p class="text-sm">Apakah Anda yakin ingin menghapus data alat berikut?</p>
                    </div>

                    <div class="overflow-hidden border border-gray-200 rounded-lg mb-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tr class="bg-gray-50 text-right">
                                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase w-1/3">Alat:</th>
                                <td class="px-4 py-3 text-sm text-gray-900 bg-white text-left italic font-medium">
                                    {{ $alat->nama_alat }}
                                </td>
                            </tr>
                            <tr class="bg-gray-50 text-right">
                                <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase w-1/3">Lokasi:</th>
                                <td class="px-4 py-3 text-sm text-gray-900 bg-white text-left italic font-medium">
                                    {{ $alat->laboratorium->nama_lab }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- <div class="flex p-4 text-sm text-blue-800 rounded-lg bg-blue-50 border border-blue-100">
                        <i class="fas fa-info-circle mr-3 mt-1 text-blue-500"></i>
                        <div>
                            <span class="font-bold">Perhatian:</span> Penghapusan alat akan mempengaruhi data mahasiswa yang terkait.
                        </div>
                    </div> --}}
                </div>

                <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="hideModal()" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-sm transition-colors">
                        <i class="fas fa-trash-alt mr-2"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </form>
@endif