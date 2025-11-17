<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rekam Medis Puskesmas</title>
    <link rel="icon" href="{{ asset('assets/iconya.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .shimmer {
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            background-size: 1000px 100%;
            animation: shimmer 3s infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 40px rgba(59, 130, 246, 0.8); }
        }
        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 float-animation"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 float-animation" style="animation-delay: 4s;"></div>
    </div>

    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAxOGMwLTkuOTQtOC4wNi0xOC0xOC0xOCIgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjEiIG9wYWNpdHk9IjAuMDUiLz48L2c+PC9zdmc+')] opacity-30"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4 lg:p-8">
        <div class="w-full max-w-md lg:max-w-5xl">
            <!-- Glass Card Container -->
            <div class="backdrop-blur-xl bg-white/10 rounded-3xl shadow-2xl border border-white/20 overflow-hidden">
                <!-- Top Accent Bar -->
                <div class="h-1.5 bg-gradient-to-r from-blue-400 via-cyan-400 to-blue-500 shimmer"></div>
                
                <div class="flex flex-col lg:flex-row">
                    <!-- Left Side - Branding (Hidden on Mobile, Visible on Desktop) -->
                    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600/30 to-cyan-600/30 backdrop-blur-sm p-10 flex-col justify-center items-center border-r border-white/10 relative overflow-hidden">
                        <!-- Decorative Elements -->
                        <div class="absolute top-10 right-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>
                        <div class="absolute bottom-10 left-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-3xl"></div>
                        
                        <div class="relative z-10 text-center space-y-6">
                            <!-- Logo -->
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl shadow-2xl pulse-glow transform hover:scale-110 transition-transform duration-500">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>

                            <!-- Brand Name -->
                            <div class="space-y-2">
                                <h1 class="text-3xl font-bold text-white leading-tight">
                                    Rekam Medis<br/>
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-300">
                                        Digital
                                    </span>
                                </h1>
                                <p class="text-blue-200 text-base font-light">
                                    Sistem Informasi Kesehatan Terintegrasi
                                </p>
                            </div>

                            <!-- Features -->
                            <div class="space-y-3 pt-6">
                                <div class="flex items-center space-x-3 text-left backdrop-blur-sm bg-white/5 rounded-xl p-3 border border-white/10">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold text-sm">Keamanan Terjamin</p>
                                        <p class="text-blue-200 text-xs">Enkripsi end-to-end</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 text-left backdrop-blur-sm bg-white/5 rounded-xl p-3 border border-white/10">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold text-sm">Multi-User</p>
                                        <p class="text-blue-200 text-xs">Kolaborasi tim medis</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="w-full lg:w-1/2 p-6 lg:p-10">
                        <!-- Mobile Logo (Visible on Mobile Only) -->
                        <div class="lg:hidden text-center mb-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl shadow-lg mb-3 transform hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h1 class="text-xl font-bold text-white mb-1">Rekam Medis Digital</h1>
                            <p class="text-blue-200 text-xs">Sistem Informasi Kesehatan</p>
                        </div>

                        <!-- Welcome Text -->
                        <div class="mb-6">
                            <h2 class="text-2xl lg:text-3xl font-bold text-white mb-2">Selamat Datang</h2>
                            <p class="text-blue-200 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
                        </div>

                        <!-- Error Alert (Demo) -->
                        <div class="hidden mb-5 backdrop-blur-sm bg-red-500/20 border border-red-400/50 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-300 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-red-100 text-sm font-medium">Error message here</p>
                                </div>
                            </div>
                        </div>

                        <!-- Success Alert (Demo) -->
                        <div class="hidden mb-5 backdrop-blur-sm bg-green-500/20 border border-green-400/50 rounded-xl p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-300 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-green-100 text-sm font-medium">Success message here</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
                            @csrf
                            <!-- Email Input -->
                            <div class="space-y-2">
                                <label for="email" class="block text-sm font-semibold text-blue-100">
                                    Alamat Email
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-300 group-focus-within:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                    </div>
                                    <input 
                                        type="email" 
                                        id="email"
                                        name="email" 
                                        class="block w-full pl-11 pr-4 py-3 backdrop-blur-sm bg-white/10 border border-white/20 text-white placeholder-blue-300 rounded-xl focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all duration-200 outline-none text-sm" 
                                        placeholder="nama@email.com"
                                        required 
                                        autofocus
                                    >
                                </div>
                            </div>

                            <!-- Password Input -->
                            <div class="space-y-2">
                                <label for="password" class="block text-sm font-semibold text-blue-100">
                                    Kata Sandi
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-blue-300 group-focus-within:text-cyan-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    </div>
                                    <input 
                                        type="password" 
                                        id="password"
                                        name="password" 
                                        class="block w-full pl-11 pr-4 py-3 backdrop-blur-sm bg-white/10 border border-white/20 text-white placeholder-blue-300 rounded-xl focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all duration-200 outline-none text-sm" 
                                        placeholder="••••••••••"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="group relative w-full mt-6 bg-gradient-to-r from-blue-500 via-cyan-500 to-blue-500 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg hover:shadow-cyan-500/50 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:ring-offset-2 focus:ring-offset-slate-900 transform hover:scale-[1.02] transition-all duration-200"
                            >
                                <span class="relative flex items-center justify-center text-sm">
                                    <span>Masuk ke Sistem</span>
                                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </span>
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="relative my-5">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-white/10"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 backdrop-blur-sm bg-white/5 text-blue-200 rounded-full text-xs">atau</span>
                            </div>
                        </div>

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="text-blue-200 text-sm mb-3">
                                Belum memiliki akun?
                            </p>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-full backdrop-blur-sm bg-white/5 hover:bg-white/10 border border-white/20 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 group text-sm">
                                <span>Daftar Akun Baru</span>
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </a>
                        </div>

                        <!-- Footer Text (Mobile) -->
                        <div class="lg:hidden mt-6 text-center space-y-2">
                            <p class="text-blue-200/60 text-xs">
                                Sistem Informasi Rekam Medis Elektronik
                            </p>
                            <p class="text-blue-200/40 text-xs">
                                © 2025 Puskesmas Digital
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Text (Desktop) -->
            <div class="hidden lg:block mt-6 text-center space-y-2">
                <p class="text-blue-200/60 text-sm">
                    Sistem Informasi Rekam Medis Elektronik
                </p>
                <p class="text-blue-200/40 text-xs">
                    © 2024 Puskesmas Digital. Dilindungi oleh enkripsi end-to-end
                </p>
            </div>
        </div>
    </div>
</body>
</html>