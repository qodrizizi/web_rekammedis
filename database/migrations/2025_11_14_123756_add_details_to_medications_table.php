<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            // Tambahkan kolom yang dibutuhkan
            $table->string('kode_obat', 100)->unique()->after('id');
            $table->string('kategori', 100)->nullable()->after('nama_obat');
            $table->date('tanggal_kedaluwarsa')->nullable()->after('harga');
        });
    }

    public function down(): void
    {
        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn(['kode_obat', 'kategori', 'tanggal_kedaluwarsa']);
        });
    }
};