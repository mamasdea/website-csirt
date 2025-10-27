<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('poll_voters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->timestamps();

            // Prevent duplicate votes
            $table->unique(['poll_id', 'ip_address']);
            $table->index('ip_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('poll_voters');
    }
};
