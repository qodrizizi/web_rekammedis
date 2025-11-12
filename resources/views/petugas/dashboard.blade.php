@extends('layouts.app')

@section('title', 'Dashboard Petugas Pendaftaran')

@section('content')

<div class="space-y-8">
    
    <header class="bg-white p-6 rounded-2xl shadow-xl border-l-8 border-yellow-500 transition duration-300 transform hover:scale-[1.005] hover:shadow-2xl">
        <h1 class="text-4xl font-extrabold text-gray-800 flex items-center">
            <i class="bi bi-person-badge-fill text-yellow-600 mr-3"></i> Dashboard Petugas Pendaftaran
        </h1>
        <p class="text-lg text-gray-600 mt-2">
            Selamat datang kembali, **Petugas Rina**! 
            Fokus Anda hari ini adalah kelancaran alur pendaftaran pasien.
        </p>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pasien Terdaftar</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">1,250</p>
            </div>
            <div class="bg-primary/10 p-3 rounded-xl text-primary flex items-center justify-center">
                <i class="bi bi-people-fill text-3xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Antrian Hari Ini</p>
                <p class="text-3xl font-bold text-red-600 mt-1">12</p>
            </div>
            <div class="bg-red-500/10 p-3 rounded-xl text-red-600 flex items-center justify-center">
                <i class="bi bi-calendar-minus-fill text-3xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Pasien Baru Terdaftar Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">4</p>
            </div>
            <div class="bg-blue-500/10 p-3 rounded-xl text-blue-600 flex items-center justify-center">
                <i class="bi bi-person-plus-fill text-3xl"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 flex items-center justify-between border border-gray-100">
            <div>
                <p class="text-sm font-medium text-gray-500">Verifikasi BPJS Tertunda</p>
                <p class="text-3xl font-bold text-orange-600 mt-1">3</p>
            </div>
            <div class="bg-orange-500/10 p-3 rounded-xl text-orange-600 flex items-center justify-center">
                <i class="bi bi-patch-exclamation-fill text-3xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-list-ol text-red-600 mr-2"></i> Antrian Kunjungan Aktif Hari Ini
                </h2>
                <div class="divide-y divide-gray-200">
                    
                    <div class="flex justify-between items-center py-3 border-l-4 border-yellow-500 pl-3">
                        <div>
                            <p class="font-semibold text-gray-900">1. Pasien: Budi Santoso</p>
                            <p class="text-sm text-gray-500">Poli Umum - Dr. Rian</p>
                        </div>
                        <span class="text-xs bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-medium">Sedang Diperiksa</span>
                        <button class="text-gray-500 hover:text-gray-700 text-sm font-medium">Lacak &rarr;</button>
                    </div>

                    <div class="flex justify-between items-center py-3 hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                        <div>
                            <p class="font-semibold text-gray-900">2. Pasien: Siti Nurhaliza</p>
                            <p class="text-sm text-gray-500">Poli Gigi - Dr. Amelia</p>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-medium">Menunggu Panggilan</span>
                        <button class="text-primary hover:text-secondary text-sm font-medium">Panggil &rarr;</button>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 hover:bg-gray-50 rounded-lg -mx-2 px-2 transition">
                        <div>
                            <p class="font-semibold text-gray-900">3. Pasien: Joko Susilo</p>
                            <p class="text-sm text-gray-500">Poli Anak - Dr. Santi</p>
                        </div>
                        <span class="text-xs bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium">Baru Terdaftar</span>
                        <button class="text-primary hover:text-secondary text-sm font-medium">Panggil &rarr;</button>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <a href="#" class="text-primary hover:text-secondary text-sm font-medium">Lihat Semua Antrian &rarr;</a>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-speedometer text-blue-600 mr-2"></i> Pendaftaran Pasien Cepat
                </h2>
                <form class="space-y-4">
                    <input type="text" placeholder="Nama Lengkap Pasien" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <input type="text" placeholder="NIK/No. BPJS" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                    <select class="w-full p-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option>Pilih Tujuan Poli</option>
                        <option>Poli Umum</option>
                        <option>Poli Gigi</option>
                        <option>Poli Anak</option>
                    </select>
                    <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-3 rounded-lg transition-colors duration-300 flex items-center justify-center shadow-md">
                        <i class="bi bi-file-earmark-plus-fill mr-2"></i> Daftarkan Kunjungan
                    </button>
                </form>
            </div>
        </div>
        
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                    <i class="bi bi-check-square-fill text-green-600 mr-2"></i> Tugas Verifikasi & Notifikasi
                </h2>
                
                <ul class="space-y-4">
                    <li class="flex items-start space-x-3 p-2 bg-red-50 border-l-4 border-red-500 rounded-lg">
                        <div class="w-2 h-2 bg-red-500 rounded-full mt-2.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800 font-semibold">Tugas Mendesak: Verifikasi BPJS</p>
                            <p class="text-xs text-gray-600">3 pasien menunggu verifikasi eligibility BPJS.</p>
                            <a href="#" class="text-xs text-red-500 hover:text-red-700 font-medium">Lakukan Verifikasi &rarr;</a>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Pasien baru:</span> Rina Dewi sukses didaftarkan.</p>
                            <p class="text-xs text-gray-500">10 menit yang lalu</p>
                        </div>
                    </li>
                    <li class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                        <div class="w-2 h-2 bg-primary rounded-full mt-2.5 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm text-gray-800"><span class="font-semibold">Tugas:</span> Arsip formulir fisik P1029.</p>
                            <p class="text-xs text-gray-500">Hari ini</p>
                        </div>
                    </li>
                </ul>
                <div class="mt-4 text-center">
                    <a href="#" class="text-primary hover:text-secondary text-sm font-medium">Lihat Semua Tugas &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection