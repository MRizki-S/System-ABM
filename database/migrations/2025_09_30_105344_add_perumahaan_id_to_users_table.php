<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('perumahaan_id')->nullable()->after('id');

            $table->foreign('perumahaan_id')
                ->references('id')
                ->on('perumahaan')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['perumahaan_id']);
            $table->dropColumn('perumahaan_id');
        });
    }
};
