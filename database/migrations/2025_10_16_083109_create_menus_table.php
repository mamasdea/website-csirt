<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('link', 255)->nullable();
            $table->tinyInteger('is_parent')->default(0)->comment('0 = no, 1 = yes');
            $table->unsignedBigInteger('parent_id')->nullable()->index()->comment('relasi ke id induk');
            $table->integer('order')->default(0)->comment('urutan tampil');
            $table->tinyInteger('type')->default(0)->comment('tipe menu, custom sesuai kebutuhan');
            $table->timestamps();

            // relasi opsional (tidak wajib, tapi baik untuk referensi)
            $table->foreign('parent_id')
                ->references('id')
                ->on('menus')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
