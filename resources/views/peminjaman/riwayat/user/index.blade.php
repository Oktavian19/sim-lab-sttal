@extends('layouts.app')

@section('content')
    <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            @php $currentStatus = request('status', 'all'); @endphp

            <div
                class="flex space-x-1 bg-white p-1 rounded-lg border border-gray-200 shadow-sm w-full sm:w-auto overflow-x-auto">
                @foreach (['all' => 'Semua', 'pending' => 'Menunggu', 'disetujui' => 'Disetujui', 'dipinjam' => 'Dipinjam', 'selesai' => 'Selesai'] as $key => $label)
                    <a href="{{ route('peminjaman.riwayatUser', ['status' => $key]) }}"
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors whitespace-nowrap 
                   {{ $currentStatus == $key ? 'bg-indigo-50 text-indigo-700 shadow-sm ring-1 ring-indigo-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="relative w-full sm:w-64">
                <input type="text" id="searchInput" value="{{ request('search') }}" placeholder="Cari kegiatan atau lab..."
                    class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all" />

                <i id="searchIcon" class="fa-solid fa-magnifying-glass w-4 h-4 text-gray-400 absolute left-3 top-3"></i>
                <i id="loadingIcon"
                    class="fa-solid fa-spinner fa-spin w-4 h-4 text-indigo-500 absolute left-3 top-3 hidden"></i>
            </div>
        </div>

        <div id="dataContainer" class="space-y-4">
            @include('peminjaman.riwayat.user.list')
        </div>

        <div id="myModal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4 sm:p-6 transition-opacity">
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        let searchTimer;

        $(document).ready(function() {

            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimer);
                let query = $(this).val();

                $('#searchIcon').addClass('hidden');
                $('#loadingIcon').removeClass('hidden');

                searchTimer = setTimeout(function() {
                    fetchData(query, null);
                }, 500);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();

                $('#searchIcon').addClass('hidden');
                $('#loadingIcon').removeClass('hidden');

                let url = $(this).attr('href');
                fetchData(null, url);
            });

            function fetchData(query = null, url = null) {
                if (query === null) {
                    query = $('#searchInput').val();
                }

                let urlParams = new URLSearchParams(window.location.search);
                let status = urlParams.get('status') || 'all';

                let ajaxUrl = url ? url : "{{ route('peminjaman.riwayatUser') }}";

                $.ajax({
                    url: ajaxUrl,
                    type: "GET",
                    data: {
                        search: url ? undefined : query,
                        status: url ? undefined : status
                    },
                    success: function(response) {
                        $('#dataContainer').html(response);

                        $('#loadingIcon').addClass('hidden');
                        $('#searchIcon').removeClass('hidden');

                        let historyUrl = url ? url : (window.location.pathname + '?status=' + status + (
                            query ? '&search=' + query : ''));

                        window.history.pushState({
                            path: historyUrl
                        }, '', historyUrl);
                    },
                    error: function(xhr) {
                        console.log('Error:', xhr);
                        $('#loadingIcon').addClass('hidden');
                        $('#searchIcon').removeClass('hidden');
                    }
                });
            }
        });

        function confirmCancel(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Batalkan pengajuan?',
                text: "Pengajuan yang dibatalkan tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });

            return false;
        }

        function modalAction(url) {
            $('#myModal').load(url, function(response, status, xhr) {
                if (status == "error") {
                    alert('Gagal memuat data');
                } else {
                    $('#myModal').removeClass('hidden').addClass('flex');
                    document.body.classList.add('overflow-hidden');
                }
            });
        }

        function hideModal() {
            $('#myModal').addClass('hidden').removeClass('flex');
            $('#myModal').html('');
            document.body.classList.remove('overflow-hidden');
        }

        $('#myModal').on('click', function(e) {
            if (e.target === this) {
                hideModal();
            }
        });
    </script>
@endpush
