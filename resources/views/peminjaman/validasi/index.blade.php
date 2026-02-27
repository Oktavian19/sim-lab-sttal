@extends('layouts.app')

@section('title', 'Peminjaman - SIM LAB STTAL Admin')
@section('header', 'Validasi Peminjaman')

@section('content')
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">

        <div class="bg-white rounded-lg shadow-md border border-slate-200">

            <div class="flex flex-col md:flex-row justify-between items-center p-6 border-b border-slate-200 gap-4">
                <h5 class="text-lg font-semibold text-slate-700">Peminjaman yang perlu Validasi</h5>
            </div>

            <div class="p-6">
                <table id="inventoryTable" class="w-full text-sm text-left text-slate-500 display" style="width:100%">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50">
                        <tr>
                            <th class="px-4 py-3">Tanggal Pengajuan</th>
                            <th class="px-4 py-3">Peminjam</th>
                            <th class="px-4 py-3">Rencana Pinjam</th>
                            <th class="px-4 py-3">Kegiatan</th>
                            <th class="px-4 py-3">Jumlah Peserta</th>
                            <th class="px-4 py-3 rounded-tr-lg !text-center">Aksi</th>
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
                ajax: "{{ url('peminjaman/listValidasi') }}",
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
                        data: 'tanggal_pengajuan',
                        name: 'tanggal_pengajuan',
                        className: 'px-4 py-3'
                    },
                    {
                        data: 'peminjam',
                        name: 'peminjam',
                        className: 'px-4 py-3'
                    },
                    {
                        data: 'rencana_pinjam',
                        name: 'rencana_pinjam',
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
                        let errorListHtml = '';

                        if (res.errors && Array.isArray(res.errors)) {
                            errorListHtml = res.errors.map(err => `
                            <div style="
                                background-color: #fef2f2; 
                                border-left: 5px solid #ef4444; 
                                padding: 12px; 
                                margin-bottom: 8px; 
                                border-radius: 4px; 
                                display: flex; 
                                align-items: start;
                                font-size: 0.95em;
                            ">
                                <div style="color: #ef4444; margin-right: 10px; font-weight: bold; font-size: 1.1em;">
                                    •
                                </div>
                                <div style="color: #7f1d1d; line-height: 1.4;">
                                    ${err}
                                </div>
                            </div>
                        `).join('');
                        }

                        Swal.fire({
                            icon: 'warning',
                            title: res.title ??
                                'Jadwal Bentrok',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Cek Jadwal Kembali',
                            width: '32rem',
                            html: `
                                <div style="text-align: left; font-family: sans-serif;">
                                    <p style="margin-bottom: 15px; color: #4b5563; font-size: 1.05em;">
                                        ${res.message}
                                    </p>
                                    
                                    <div style="
                                        max-height: 250px; 
                                        overflow-y: auto; 
                                        padding-right: 5px;
                                        scrollbar-width: thin;
                                        scrollbar-color: #cbd5e1 transparent;
                                    ">
                                        ${errorListHtml}
                                    </div>
                                </div>
                            `,
                            didOpen: () => {
                                Swal.getConfirmButton().blur();
                            }
                        });
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
