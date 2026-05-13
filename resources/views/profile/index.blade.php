@extends('layouts.app')

@section('title', 'Profil Saya - Rekam Medis')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pengaturan Profil</h2>
            <p class="text-gray-600">Kelola informasi akun dan keamanan Anda</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="bi bi-check-circle-fill text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Sisi Kiri: Foto Profil --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="relative inline-block group">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-primary/20 shadow-lg mb-4 mx-auto bg-gray-50 flex items-center justify-center">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" id="previewAvatar" class="w-full h-full object-cover">
                        @else
                            <div id="placeholderAvatar" class="text-primary">
                                <i class="bi bi-person-fill text-6xl"></i>
                            </div>
                            <img id="previewAvatar" class="w-full h-full object-cover hidden">
                        @endif
                    </div>
                </div>
                <h3 class="font-bold text-gray-800">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 capitalize">{{ $user->role }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $user->email }}</p>
            </div>

            <div class="bg-gradient-to-br from-primary to-secondary rounded-2xl shadow-lg p-6 text-white">
                <h4 class="font-semibold mb-2 flex items-center">
                    <i class="bi bi-shield-lock-fill mr-2"></i>
                    Keamanan Akun
                </h4>
                <p class="text-sm text-white/80">Pastikan password Anda kuat dan unik untuk menjaga keamanan data medis Anda.</p>
            </div>
        </div>

        {{-- Sisi Kanan: Form Update --}}
        <div class="md:col-span-2 space-y-6">
            {{-- Form Informasi Dasar --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <i class="bi bi-person-gear mr-2 text-primary"></i>
                        Informasi Dasar
                    </h3>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil (Opsional)</label>
                            <input type="file" name="avatar" id="avatarInput" accept="image/*"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-1">*Format: JPG, PNG, GIF. Max: 2MB</p>
                            @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="bg-primary hover:bg-secondary text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-primary/20 transition-all active:scale-95 flex items-center">
                            <i class="bi bi-check2-circle mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Form Ganti Password --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center">
                        <i class="bi bi-key mr-2 text-primary"></i>
                        Ganti Password
                    </h3>
                </div>
                <form action="{{ route('profile.password') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <input type="password" name="current_password" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                            @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none">
                        </div>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="bg-slate-800 hover:bg-black text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-black/10 transition-all active:scale-95 flex items-center">
                            <i class="bi bi-shield-check mr-2"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Image Preview
    const avatarInput = document.getElementById('avatarInput');
    const previewAvatar = document.getElementById('previewAvatar');
    const placeholderAvatar = document.getElementById('placeholderAvatar');

    avatarInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewAvatar.src = e.target.result;
                previewAvatar.classList.remove('hidden');
                if (placeholderAvatar) placeholderAvatar.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
