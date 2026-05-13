<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->string('status_pengambilan_obat')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            // Kembali ke enum jika perlu, tapi string lebih fleksibel
            $table->enum('status_pengambilan_obat', ['menunggu', 'disiapkan', 'selesai'])->nullable()->change();
        });
    }
};
