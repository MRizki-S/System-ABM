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
        Schema::table('punishment', function (Blueprint $table) {
            if (Schema::hasColumn('punishment', 'keterlambatan')) {
                $table->dropColumn('keterlambatan');
            }

            $table->time('jam_keterlambatan')->nullable()->after('absensi_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('punishment', function (Blueprint $table) {
            $table->dropColumn('jam_keterlambatan');
            $table->integer('keterlambatan')->nullable()->after('absensi_id');
        });
    }
};
