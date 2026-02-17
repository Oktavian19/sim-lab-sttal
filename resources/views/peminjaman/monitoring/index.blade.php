@extends('layouts.app')

@section('title', 'Peminjaman - SIM LAB STTAL Admin')
@section('header', 'Monitoring Peminjaman')

@section('content')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">

        <div class="bg-white rounded-lg shadow-md border border-slate-200">

            <div class="flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-200 gap-4">
                <h5 class="text-lg font-semibold text-slate-700">Peminjaman yang sedang Dipantau</h5>
            </div>

            <div class="p-6">
                <table id="inventoryTable" class="w-full text-sm text-left text-slate-500 display" style="width:100%">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                        <tr>
                            <th class="px-4 py-3">Mulai Pinjam</th>
                            <th class="px-4 py-3">Peminjam</th>
                            <th class="px-4 py-3">Kegiatan</th>
                            <th class="px-4 py-3">Jumlah Peserta</th>
                            <th class="px-4 py-3">Tenggat Waktu</th>
                            <th class="px-4 py-3 rounded-tr-lg text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div id="myModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        </div>


    </main>
@endsection
@push('scripts')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                showModal();
            });
        }

        var table;
        $(document).ready(function() {
            table = $('#inventoryTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ url('peminjaman/listMonitoring') }}",
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',

                language: {
                    search: "Cari:",
                    lengthMenu: "Tampil _MENU_",
                    info: "Hal _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data",
                    infoFiltered: "(dari _MAX_ total data)",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "<i class='fa-solid fa-chevron-right'></i>",
                        previous: "<i class='fa-solid fa-chevron-left'></i>"
                    }
                },

                columns: [{
                        data: 'mulai_pinjam',
                        name: 'mulai_pinjam',
                        className: 'px-4 py-3'
                    },
                    {
                        data: 'peminjam',
                        name: 'peminjam',
                        className: 'px-4 py-3'
                    },
                    {
                        data: 'kegiatan',
                        name: 'kegiatan',
                        className: 'px-4 py-3'
                    },
                    {
                        data: 'jumlah_peserta',
                        name: 'jumlah_peserta',
                        className: 'px-4 py-3'
                    },
                    {
                        data: 'tenggat_waktu',
                        name: 'tenggat_waktu',
                        className: 'px-4 py-3'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: 'px-4 py-3 text-center'
                    }
                ],

                createdRow: function(row, data, dataIndex) {
                    $(row).addClass('border-b hover:bg-slate-50');
                }
            });
        });

        $(document).on('submit', 'form.validate', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let method = form.attr('method');
            let data = new FormData(this);

            if ($.fn.validate && form.hasClass('validate')) {
                if (!form.valid()) return false;
            }

            $.ajax({
                url: url,
                type: method,
                data: data,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.status) {
                        hideModal();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: res.title || 'Gagal',
                            text: res.message
                        });
                        if (res.errors) {
                            $('.error-text').text('');
                            $.each(res.errors, function(field, messages) {
                                $('#error-' + field).text(messages[0]);
                            });
                        }
                    }
                },
                error: function(xhr) {
                    hideModal();
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat mengirim data.'
                    });
                }
            });
        });
    </script>
@endpush
