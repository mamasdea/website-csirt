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
        Schema::create('polls', function (Blueprint $table) {
            $table->id();
            $table->string('question'); // Pertanyaan polling, cth: "Seberapa bermanfaat situs website ini?"

            // Kolom untuk menyimpan opsi polling dan jumlah suara dalam format JSON
            // Contoh struktur JSON:
            // [
            //   {"id": 1, "text": "Sangat Bermanfaat", "votes": 65},
            //   {"id": 2, "text": "Cukup Bermanfaat", "votes": 30},
            //   {"id": 3, "text": "Kurang Bermanfaat", "votes": 5}
            // ]
            $table->json('options_with_votes');

            $table->boolean('is_active')->default(true); // Status polling
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polls');
    }
};
