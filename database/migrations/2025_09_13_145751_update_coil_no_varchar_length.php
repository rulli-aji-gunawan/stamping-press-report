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
        Schema::table('table_productions', function (Blueprint $table) {
            $table->string('coil_no', 255)->change(); // Ubah dari varchar(20) ke varchar(255)
        });

        Schema::table('table_downtimes', function (Blueprint $table) {
            $table->string('coil_no', 255)->change(); // Ubah dari varchar(20) ke varchar(255)
        });

        Schema::table('table_defects', function (Blueprint $table) {
            $table->string('coil_no', 255)->change(); // Ubah dari varchar(20) ke varchar(255)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_productions', function (Blueprint $table) {
            $table->string('coil_no', 20)->change(); // Kembalikan ke varchar(20)
        });

        Schema::table('table_downtimes', function (Blueprint $table) {
            $table->string('coil_no', 20)->change(); // Kembalikan ke varchar(20)
        });

        Schema::table('table_defects', function (Blueprint $table) {
            $table->string('coil_no', 20)->change(); // Kembalikan ke varchar(20)
        });
    }
};
