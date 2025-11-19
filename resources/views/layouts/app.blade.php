<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rekam Medis Puskesmas')</title>
    <link rel="icon" href="{{ asset('assets/iconya.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0ea5e9',
                        secondary: '#0284c7',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="flex h-screen overflow-hidden">
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden"></div>
        
        <aside id="sidebar" class="fixed lg:relative inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-primary to-secondary text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl">
            <div class="flex flex-col h-full">

                <div class="p-6 border-b border-white/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="bg-white p-2 rounded-lg shadow-lg">
                                <i class="bi bi-heart-pulse text-primary text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold">Puskesmas</h1>
                                <p class="text-xs text-white/80">Rekam Medis Digital</p>
                            </div>
                        </div>
                        <button id="closeSidebar" class="lg:hidden text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                            <i class="bi bi-x-lg text-xl"></i>
                        </button>
                    </div>
                </div>

                <nav class="flex-1 overflow-y-auto py-6 px-3">
                    <div class="space-y-1">
                        {{-- Dashboard Menu untuk semua role --}}
                        <a href="{{ url('/' . Auth::user()->role . '/dashboard') }}" 
                           class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is(Auth::user()->role . '/dashboard') ? 'bg-white/20' : '' }}">
                            <i class="bi bi-speedometer2 text-xl group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        {{-- ================== ADMIN MENU (role: admin) ================== --}}
                        @if(Auth::user()->role === 'admin')
                            <div class="pt-4 pb-2 px-4">
                                <p class="text-xs font-semibold text-white/60 uppercase tracking-wider">Data Master</p>
                            </div>
                            
                            <a href="{{ route('admin.pasien')}}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('admin/pasien*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-people text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Pasien</span>
                            </a>
                            
                            <a href="{{ route('admin.dokter')}}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('admin/dokter*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-person-badge text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Dokter</span>
                            </a>
                            
                            <a href="{{ route('admin.obat')}}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('admin/obat*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-capsule text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Obat</span>
                            </a>
                            
                            <a href="{{ route('admin.poliklinik')}}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('admin/poliklinik*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-hospital text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Poliklinik</span>
                            </a>

                            <div class="pt-4 pb-2 px-4">
                                <p class="text-xs font-semibold text-white/60 uppercase tracking-wider">Pelayanan</p>
                            </div>
                            
                            <a href="{{ route('admin.pendaftaran')}}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('admin/pendaftaran*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-calendar-check text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Pendaftaran</span>
                            </a>
                            
                            <a href="{{ route('admin.rekam_medis')}}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('admin/rekam_medis*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-clipboard2-pulse text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Rekam Medis</span>
                            </a>

                            <div class="pt-4 pb-2 px-4">
                                <p class="text-xs font-semibold text-white/60 uppercase tracking-wider">Sistem</p>
                            </div>
                            
                            <a href="{{ route('admin.roles')}}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('admin/roles*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-shield-check text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Manajemen Role</span>
                            </a>
                            
                            <a href="{{ route('admin.laporan')}}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('admin/laporan*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-file-earmark-bar-graph text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Laporan</span>
                            </a>
                        @endif

                        {{-- ================== DOKTER MENU (role: dokter) ================== --}}
                        @if(Auth::user()->role === 'dokter')
                            <div class="pt-4 pb-2 px-4">
                                <p class="text-xs font-semibold text-white/60 uppercase tracking-wider">Pelayanan Medis</p>
                            </div>
                            
                            <a href="{{ route('dokter.pasien') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('dokter/pasien*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-people text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Pasien</span>
                            </a>
                            
                            <a href="{{ route('dokter.jadwal') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('dokter/jadwal*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-clock-history text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Jadwal</span>
                            </a>

                            <a href="{{ route('dokter.rekam_medis') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('dokter/rekam_medis*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-clipboard2-pulse text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Rekam Medis</span>
                            </a>
                            
                            <a href="{{ route('dokter.laporan') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('dokter/laporan*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-file-earmark-text text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Laporan</span>
                            </a>
                        @endif

                        {{-- ================== PETUGAS MENU (role: petugas) ================== --}}
                        @if(Auth::user()->role === 'petugas')
                            <div class="pt-4 pb-2 px-4">
                                <p class="text-xs font-semibold text-white/60 uppercase tracking-wider">Administrasi</p>
                            </div>
                            
                            <a href="{{ route('petugas.pendaftaran') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('petugas/pendaftaran*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-calendar-plus text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Pendaftaran</span>
                            </a>
                            
                            <a href="{{ route('petugas.pasien') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('petugas/pasien*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-person-lines-fill text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Pasien</span>
                            </a>

                            <div class="pt-4 pb-2 px-4">
                                <p class="text-xs font-semibold text-white/60 uppercase tracking-wider">Farmasi</p>
                            </div>
                            
                            <a href="{{ route('petugas.obat') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('petugas/obat*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-capsule text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Obat</span>
                            </a>
                            
                            <a href="{{ route('petugas.resep') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('petugas/resep*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-clipboard-check text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Resep Obat</span>
                            </a>
                        @endif

                        {{-- ================== PASIEN MENU (role: pasien) ================== --}}
                        @if(Auth::user()->role === 'pasien')
                            <div class="pt-4 pb-2 px-4">
                                <p class="text-xs font-semibold text-white/60 uppercase tracking-wider">Layanan Pasien</p>
                            </div>
                            
                            <a href="{{ route('pasien.profil') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('pasien/profil*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-person text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Profil Saya</span>
                            </a>
                            
                            <a href="{{ route('pasien.rekam_medis') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('pasien/rekam_medis*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-clipboard2-heart text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Riwayat Medis</span>
                            </a>
                            
                            <a href="{{ route('pasien.konsultasi') }}" 
                               class="nav-link flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group {{ Request::is('pasien/konsultasi*') ? 'bg-white/20' : '' }}">
                                <i class="bi bi-calendar-event text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Jadwal Konsultasi</span>
                            </a>
                        @endif
                    </div>
                </nav>

                <div class="p-4 border-t border-white/20">
                    <div class="flex items-center space-x-3 px-3 py-2">
                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                            <i class="bi bi-person-fill text-primary text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-white/70 capitalize">{{ Auth::user()->role }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            {{-- Ganti type="submit" jadi type="button" dan tambah onclick --}}
                            <button type="button" onclick="confirmLogout(this)" class="hover:bg-white/20 p-2 rounded-lg transition-colors">
                                <i class="bi bi-box-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm flex-shrink-0">
                <div class="flex items-center justify-between px-4 lg:px-8 py-4">
                    <div class="flex items-center space-x-4">
                        <button id="menuToggle" class="lg:hidden text-gray-600 hover:text-primary p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="bi bi-list text-2xl"></i>
                        </button>

                        {{-- Jam Digital --}}
                        <div class="hidden sm:flex items-center space-x-2 text-gray-700">
                            <i class="bi bi-clock text-primary"></i>
                            <div>
                                <div id="digitalClock" class="text-sm font-semibold"></div>
                                <div id="digitalDate" class="text-xs text-gray-500"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1"></div>

                    <div class="flex items-center space-x-2">
                        <!-- <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="bi bi-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button> -->

                        {{-- User Dropdown --}}
                        <div class="relative hidden lg:block">
                            <button id="userMenuButton" class="flex items-center space-x-2 hover:bg-gray-100 rounded-lg p-2 transition-colors">
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                                </div>
                                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                                    <i class="bi bi-person-fill text-white"></i>
                                </div>
                                <i class="bi bi-chevron-down text-gray-500 text-xs"></i>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <!-- <a href="#" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="bi bi-person"></i>
                                    <span>Profil</span>
                                </a>
                                <a href="#" class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                    <i class="bi bi-gear"></i>
                                    <span>Pengaturan</span>
                                </a> -->
                                <!-- <hr class="my-2"> -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    {{-- Ganti type="submit" jadi type="button" dan tambah onclick --}}
                                    <button type="button" onclick="confirmLogout(this)" class="flex items-center space-x-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors w-full text-left">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 lg:p-8">
                @yield('content')
            </main>

            <footer class="bg-white border-t border-gray-200 flex-shrink-0">
                <div class="px-4 lg:px-8 py-4">
                    <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600">
                        <p>&copy; {{ date('Y') }} Puskesmas Digital. All rights reserved.</p>
                        <div class="flex space-x-4 mt-2 sm:mt-0">
                            <a href="#" class="hover:text-primary transition-colors">Bantuan</a>
                            <a href="#" class="hover:text-primary transition-colors">Privasi</a>
                            <a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script>
        // Toggle Sidebar
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const menuToggle = document.getElementById('menuToggle');
        const closeSidebar = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebarFunc() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        menuToggle.addEventListener('click', openSidebar);
        closeSidebar.addEventListener('click', closeSidebarFunc);
        overlay.addEventListener('click', closeSidebarFunc);

        // Close sidebar ketika window resize ke desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeSidebarFunc();
            }
        });

        // Digital Clock
        function updateClock() {
            const now = new Date();
            
            // Format waktu
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            // Format tanggal
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                          'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const date = now.getDate();
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            
            document.getElementById('digitalClock').textContent = `${hours}:${minutes}:${seconds}`;
            document.getElementById('digitalDate').textContent = `${dayName}, ${date} ${monthName} ${year}`;
        }

        // Update clock setiap detik
        updateClock();
        setInterval(updateClock, 1000);

        // User Dropdown Toggle
        const userMenuButton = document.getElementById('userMenuButton');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuButton && userDropdown) {
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.add('hidden');
                }
            });
        }

        function confirmLogout(button) {
        // Cari form terdekat dari tombol yang diklik
        const form = button.closest('form');

        Swal.fire({
            title: 'Yakin ingin keluar?',
            text: "Sesi Anda akan berakhir.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0ea5e9', // Warna primary (sesuai tema anda)
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
            reverseButtons: true // Posisi tombol dibalik agar 'Batal' di kiri
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik Ya, submit form secara manual
                form.submit();
            }
        });
    }
    </script>
</body>
</html>