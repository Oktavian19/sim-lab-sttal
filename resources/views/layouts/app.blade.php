<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden w-full">
        <div class="sidebar flex-none w-64 h-full bg-slate-800 text-white">
            @include('layouts.sidebar')
        </div>

        <div class="flex-1 flex flex-col h-full relative overflow-hidden">
            <div class="md:hidden absolute top-4 left-4 z-50">
                <button onclick="toggleSidebar()"
                    class="text-slate-500 hover:text-slate-700 bg-white p-2 rounded shadow">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
            </div>

            <div class="flex-1 h-full w-full flex flex-col overflow-hidden">
                <header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10 shrink-0">
                    <div class="flex items-center gap-4">
                        <button onclick="toggleSidebar()"
                            class="text-slate-500 hover:text-slate-700 focus:outline-none lg:hidden">
                            <i class="fa-solid fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-slate-800">@yield('header')</h2>
                    </div>

                    <div class="flex items-center gap-4">
                        <div
                            class="hidden md:block text-sm text-slate-600 font-medium bg-slate-100 px-3 py-1 rounded-md">
                            {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                        </div>
                    </div>
                </header>

                @yield('content')
            </div>
        </div>
    </div>

    <div class="overlay" onclick="toggleSidebar()"></div>

    <div id="myModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div id="modalContent"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('active');
        }

        function showModal() {
            $('#myModal').removeClass('hidden').addClass('flex');
        }

        function hideModal() {
            $('#myModal').addClass('hidden').removeClass('flex');
        }

        $(document).ready(function() {
            $('.submenu-toggle').on('click', function(e) {
                e.preventDefault();
                let $parent = $(this).parent('.has-submenu');
                let $submenu = $parent.find('.submenu');
                $parent.toggleClass('open');
                $submenu.slideToggle(300);
            });
        });
    </script>

    @stack('scripts')

</body>

</html>
