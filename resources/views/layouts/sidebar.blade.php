<div class="flex flex-col h-full w-full">

    @php
        $baseClass = 'flex items-center px-6 py-2.5 transition-colors group';

        $activeClass = $baseClass . ' bg-slate-700 text-white border-r-4 border-blue-500';
        $inactiveClass =
            $baseClass . ' text-slate-300 hover:bg-slate-700 hover:text-white border-r-4 border-transparent';
    @endphp

    <div class="h-16 flex items-center justify-center border-b border-slate-700 bg-slate-900">
        <div class="flex items-center gap-3">
            <i class="fa-solid fa-anchor text-blue-400 text-2xl"></i>
            <h1 class="text-xl font-bold tracking-wider">SIM LAB <span class="text-blue-400">STTAL</span></h1>
        </div>
    </div>

    <div class="p-4 border-b border-slate-700 flex items-center gap-3 bg-slate-900">
        @if (auth()->user()->photo_path)
            <img src="{{ asset('storage/' . auth()->user()->photo_path) }}" alt="User Avatar"
                class="w-10 h-10 rounded-full object-cover border border-slate-600">
        @else
            <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                {{ substr(auth()->user()->nama ?? 'U', 0, 2) }}
            </div>
        @endif
        <div>
            <p class="text-sm font-semibold text-white">{{ Str::limit(auth()->user()->nama ?? 'User', 18) }}</p>
            <p class="text-xs text-slate-400">NRP. {{ auth()->user()->nrp }}</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 space-y-1 bg-slate-800 scrollbar-thin scrollbar-thumb-slate-600">

        <a href="{{ route('dashboard') }}"
            class="{{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
            <i class="fa-solid fa-gauge w-6 group-hover:text-blue-400 transition-colors"></i>
            <span class="text-sm font-medium">Dashboard</span>
        </a>

        @if (auth()->user()->role === 'admin')
            <p class="px-6 mt-6 mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">Master Data</p>

            @php $isUserMenuOpen = request()->routeIs('user.*'); @endphp
            <div x-data="{ open: {{ $isUserMenuOpen ? 'true' : 'false' }} }" @click.outside="open = false">
                <button @click="open = !open"
                    class="w-full flex items-center px-6 py-2.5 transition-colors group {{ $isUserMenuOpen ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fa-solid fa-users w-6 group-hover:text-blue-400 transition-colors"></i>
                    <span class="text-sm font-medium">Data Pengguna</span>
                    <i class="fa-solid fa-chevron-down ml-auto text-xs transition-transform duration-200"
                        :class="{ 'rotate-180': open }">
                    </i>
                </button>

                <div x-show="open" x-collapse class="bg-slate-900 pl-12 py-2 space-y-1">
                    <a href="{{ route('user.index', ['role' => 'admin']) }}"
                        class="block py-2 text-sm hover:text-white transition-colors {{ request()->routeIs('user.admin.*') ? 'text-white font-semibold' : 'text-slate-400' }}">
                        Data Admin
                    </a>
                    <a href="{{ route('user.index', ['role' => 'user']) }}"
                        class="block py-2 text-sm hover:text-white transition-colors {{ request()->routeIs('user.user.*') ? 'text-white font-semibold' : 'text-slate-400' }}">
                        Data User
                    </a>
                </div>
            </div>

            <a href="{{ route('alat.index') }}"
                class="{{ request()->routeIs('alat.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-microscope w-6 group-hover:text-blue-400 transition-colors"></i>
                <span class="text-sm font-medium">Inventaris Alat</span>
            </a>

            <a href="{{ route('laboratorium.index') }}"
                class="{{ request()->routeIs('laboratorium.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-flask w-6 group-hover:text-blue-400 transition-colors"></i>
                <span class="text-sm font-medium">Data Laboratorium</span>
            </a>

            <p class="px-6 mt-6 mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">Validasi & Transaksi</p>

            @php $isBookingMenuOpen = request()->routeIs('peminjaman.*'); @endphp
            <div x-data="{ open: {{ $isBookingMenuOpen ? 'true' : 'false' }} }" @click.outside="open = false">
                <button @click="open = !open"
                    class="w-full flex items-center px-6 py-2.5 transition-colors group {{ $isBookingMenuOpen ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-check w-6 group-hover:text-green-400 transition-colors"></i>
                    <span class="text-sm font-medium">Peminjaman</span>
                    <i class="fa-solid fa-chevron-down ml-auto text-xs transition-transform duration-200"
                        :class="{ 'rotate-180': open }">
                    </i>
                </button>

                <div x-show="open" x-collapse class="bg-slate-900 pl-12 py-2 space-y-1">
                    <a href="{{ route('peminjaman.validasi') }}"
                        class="block py-2 text-sm hover:text-white transition-colors {{ request()->routeIs('validasi.*') ? 'text-white font-semibold' : 'text-slate-400' }}">
                        Validasi Peminjaman
                    </a>
                    <a href="{{ route('peminjaman.monitoring') }}"
                        class="block py-2 text-sm hover:text-white transition-colors {{ request()->routeIs('monitoring.*') ? 'text-white font-semibold' : 'text-slate-400' }}">
                        Monitoring Peminjaman
                    </a>
                    <a href="{{ route('peminjaman.riwayat') }}"
                        class="block py-2 text-sm hover:text-white transition-colors {{ request()->routeIs('riwayat.*') ? 'text-white font-semibold' : 'text-slate-400' }}">
                        Riwayat Peminjaman
                    </a>
                </div>
            </div>

            <a href="{{ route('laporan.index') }}"
                class="{{ request()->routeIs('laporan.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-triangle-exclamation w-6 group-hover:text-red-400 transition-colors"></i>
                <span class="text-sm font-medium">Laporan Kerusakan</span>
            </a>
        @else
            <p class="px-6 mt-6 mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">Layanan Laboratorium</p>

            @php $isUserBookingOpen = request()->routeIs('alat.*') || request()->routeIs('laboratorium.*'); @endphp
            <div x-data="{ open: {{ $isUserBookingOpen ? 'true' : 'false' }} }" @click.outside="open = false">
                <button @click="open = !open"
                    class="w-full flex items-center px-6 py-2.5 transition-colors group {{ $isUserBookingOpen ? 'bg-slate-700 text-white' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="fa-solid fa-users w-6 group-hover:text-blue-400 transition-colors"></i>
                    <span class="text-sm font-medium">Peminjaman</span>
                    <i class="fa-solid fa-chevron-down ml-auto text-xs transition-transform duration-200"
                        :class="{ 'rotate-180': open }">
                    </i>
                </button>

                <div x-show="open" x-collapse class="bg-slate-900 pl-12 py-2 space-y-1">
                    <a href="{{ route('peminjaman.create') }}"
                        class="block py-2 text-sm hover:text-white transition-colors {{ request()->routeIs('peminjaman.*') ? 'text-white font-semibold' : 'text-slate-400' }}">
                        Ajukan Peminjaman
                    </a>
                    <a href="#"
                        class="block py-2 text-sm hover:text-white transition-colors {{ request()->routeIs('alat.*') ? 'text-white font-semibold' : 'text-slate-400' }}">
                        Data Alat
                    </a>
                    <a href="#"
                        class="block py-2 text-sm hover:text-white transition-colors {{ request()->routeIs('laboratorium.*') ? 'text-white font-semibold' : 'text-slate-400' }}">
                        Data Laboratorium
                    </a>
                </div>
            </div>

            <a href="{{ route('peminjaman.schedule') }}" class="{{ request()->routeIs('peminjaman.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-list-check w-6 group-hover:text-blue-400 transition-colors"></i>
                <span class="text-sm font-medium">Jadwal Peminjaman</span>
            </a>

            <p class="px-6 mt-6 mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">Pusat Bantuan</p>

            <a href="#" class="{{ request()->routeIs('laporan.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-triangle-exclamation w-6 group-hover:text-red-400 transition-colors"></i>
                <span class="text-sm font-medium">Lapor Kerusakan</span>
            </a>

            <a href="#" class="{{ request()->routeIs('riwayat.*') ? $activeClass : $inactiveClass }}">
                <i class="fa-solid fa-clock-rotate-left w-6 group-hover:text-yellow-400 transition-colors"></i>
                <span class="text-sm font-medium">Riwayat Transaksi</span>
            </a>
        @endif
    </nav>

    <div class="p-4 border-t border-slate-700 bg-slate-900">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white py-2 rounded-md transition-colors text-sm font-medium">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>
    </div>
</div>
