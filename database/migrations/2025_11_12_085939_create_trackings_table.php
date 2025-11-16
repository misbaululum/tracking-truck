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
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_name');
            $table->string('plate_number');
            $table->text('description');
            $table->datetime('security_start')->nullable();
            $table->datetime('security_end')->nullable();
            $table->datetime('loading_start')->nullable();
            $table->datetime('loading_end')->nullable();
            $table->datetime('ttb_start')->nullable();
            $table->datetime('ttb_end')->nullable();
            $table->string('current_stage');
            $table->timestamps();

            // Optimasi ditambahkan di sini
            $table->index('plate_number');
            $table->index('current_stage');
        });
    }

    /**
     * Reverse the migrations.
     */
    // 👇 Ini adalah baris yang diperbaiki (sebelumnya 'publicS')
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};