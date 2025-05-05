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
        DB::statement("ALTER TABLE form_statuses MODIFY result_status ENUM('inprogress', 'submitting', 'confirmed','Winner', 'Loser', 'Error') NULL");
        DB::table('form_statuses')->update([
            'result_status' => DB::raw('status')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_statuses', function (Blueprint $table) {
            //
        });
    }
};
