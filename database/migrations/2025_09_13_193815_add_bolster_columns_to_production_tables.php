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
        // Add bolster columns to table_productions
        Schema::table('table_productions', function (Blueprint $table) {
            $table->string('bolster_1', 10)->nullable()->after('coil_no');
            $table->string('bolster_2', 10)->nullable()->after('bolster_1');
            $table->string('bolster_3', 10)->nullable()->after('bolster_2');
            $table->string('bolster_4', 10)->nullable()->after('bolster_3');
        });

        // Add bolster columns to table_downtimes
        Schema::table('table_downtimes', function (Blueprint $table) {
            $table->string('bolster_1', 10)->nullable()->after('coil_no');
            $table->string('bolster_2', 10)->nullable()->after('bolster_1');
            $table->string('bolster_3', 10)->nullable()->after('bolster_2');
            $table->string('bolster_4', 10)->nullable()->after('bolster_3');
        });

        // Add bolster columns to table_defects
        Schema::table('table_defects', function (Blueprint $table) {
            $table->string('bolster_1', 10)->nullable()->after('coil_no');
            $table->string('bolster_2', 10)->nullable()->after('bolster_1');
            $table->string('bolster_3', 10)->nullable()->after('bolster_2');
            $table->string('bolster_4', 10)->nullable()->after('bolster_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove bolster columns from table_productions
        Schema::table('table_productions', function (Blueprint $table) {
            $table->dropColumn(['bolster_1', 'bolster_2', 'bolster_3', 'bolster_4']);
        });

        // Remove bolster columns from table_downtimes
        Schema::table('table_downtimes', function (Blueprint $table) {
            $table->dropColumn(['bolster_1', 'bolster_2', 'bolster_3', 'bolster_4']);
        });

        // Remove bolster columns from table_defects
        Schema::table('table_defects', function (Blueprint $table) {
            $table->dropColumn(['bolster_1', 'bolster_2', 'bolster_3', 'bolster_4']);
        });
    }
};
