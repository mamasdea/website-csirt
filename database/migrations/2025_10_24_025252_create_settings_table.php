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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Kolom utama untuk informasi umum
            $table->string('name')->nullable();        // Nama aplikasi/situs
            $table->text('description')->nullable();  // Deskripsi singkat atau tagline

            // Kolom kontak
            $table->string('no_telp')->nullable();     // Nomor telepon
            $table->string('email')->nullable();       // Alamat email
            $table->text('address')->nullable();       // Alamat fisik

            // Kolom tautan/embed
            $table->string('link_aduan')->nullable();  // Tautan ke halaman pengaduan
            $table->text('maps_embed')->nullable();    // Kode embed Google Maps (menggunakan 'text' karena kodenya panjang)

            // Kolom file (gambar)
            $table->string('logo')->nullable();        // Path ke file logo

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
