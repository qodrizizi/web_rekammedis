<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rekam Medis Puskesmas')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        <a href="{{ url('/' . Auth::user()->role . '/dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                            <i class="bi bi-speedometer2 text-xl group-hover:scale-110 transition-transform"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        {{-- ================== ADMIN MENU (role_id: 1) ================== --}}
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.pasien')}}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-people text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Pasien</span>
                            </a>
                            <a href="{{ route('admin.rekam_medis')}}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-clipboard2-pulse text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Rekam Medis</span>
                            </a>
                            <a href="{{ route('admin.pendaftaran')}}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-calendar-check text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Pendaftaran</span>
                            </a>
                            <a href="{{ route('admin.dokter')}}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-person-badge text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Dokter</span>
                            </a>
                            <a href="{{ route('admin.obat')}}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-capsule text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Data Obat</span>
                            </a>
                            <a href="{{ route('admin.laporan')}}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-file-earmark-bar-graph text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium">Laporan</span>
                            </a>
                        @endif

                        {{-- ================== DOKTER MENU (role_id: 2) ================== --}}
                        @if(Auth::user()->role === 'dokter')
                            <a href="{{ route('dokter.pasien') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-people text-xl"></i>
                                <span class="font-medium">Data Pasien</span>
                            </a>
                            <a href="{{ route('dokter.rekam_medis') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-clipboard2-pulse text-xl"></i>
                                <span class="font-medium">Rekam Medis</span>
                            </a>
                            <a href="{{ route('dokter.laporan') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-file-earmark-text text-xl"></i>
                                <span class="font-medium">Laporan</span>
                            </a>
                        @endif

                        {{-- ================== PETUGAS MENU (role_id: 3) ================== --}}
                        @if(Auth::user()->role === 'petugas')
                            <a href="{{ route('petugas.pendaftaran') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-calendar-plus text-xl"></i>
                                <span class="font-medium">Pendaftaran</span>
                            </a>
                            <a href="{{ route('petugas.pasien') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-person-lines-fill text-xl"></i>
                                <span class="font-medium">Data Pasien</span>
                            </a>
                            {{-- Menu Apoteker (Jika Apoteker adalah Petugas) --}}
                            <a href="{{ route('petugas.obat') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-capsule text-xl"></i>
                                <span class="font-medium">Data Obat</span>
                            </a>
                            <a href="{{ route('petugas.resep') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-clipboard-check text-xl"></i>
                                <span class="font-medium">Resep Obat</span>
                            </a>
                        @endif

                        {{-- ================== PASIEN MENU (role_id: 4) ================== --}}
                        @if(Auth::user()->role === 'pasien')
                            <a href="{{ route('pasien.rekam_medis') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-clipboard2-heart text-xl"></i>
                                <span class="font-medium">Riwayat Rekam Medis</span>
                            </a>
                            <a href="{{ route('pasien.konsultasi') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-calendar-event text-xl"></i>
                                <span class="font-medium">Jadwal Konsultasi</span>
                            </a>
                            <a href="{{ route('pasien.profil') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-white/10 transition-all duration-200 group">
                                <i class="bi bi-person text-xl"></i>
                                <span class="font-medium">Profil</span>
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
                            <button type="submit" class="hover:bg-white/20 p-2 rounded-lg transition-colors">
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
                    <button id="menuToggle" class="lg:hidden text-gray-600 hover:text-primary p-2 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="bi bi-list text-2xl"></i>
                    </button>

                    <div class="flex-1 max-w-xl mx-4 hidden sm:block">
                        <div class="relative">
                            <input type="text" placeholder="Cari pasien, dokter, atau rekam medis..." 
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                            <i class="bi bi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button class="sm:hidden p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="bi bi-search text-xl"></i>
                        </button>
                        
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="bi bi-bell text-xl"></i>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <div class="hidden lg:flex items-center space-x-2 ml-2">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ Auth::user()->role }}</p>
                            </div>
                            <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center">
                                <i class="bi bi-person-fill text-white"></i>
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
    </script>
</body>
</html>