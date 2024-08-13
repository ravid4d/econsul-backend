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
        Schema::create('form_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_detail_id')->unique()->constrained()->onDelete('cascade');
            $table->enum('status',["submit","underprocess"]);
            $table->string("confirmation_no")->nullable();
            $table->string("digital_sign")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_statuses');
    }
};
