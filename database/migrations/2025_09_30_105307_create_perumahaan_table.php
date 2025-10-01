<?php

// database/migrations/2025_09_30_000000_create_perumahaan_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('perumahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('radius')->default(50); // default 50 meter
            $table->string('wa_group_id')->nullable(); // ID group WA untuk notifikasi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perumahaan');
    }
};

