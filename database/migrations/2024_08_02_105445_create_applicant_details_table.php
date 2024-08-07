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
        Schema::create('applicant_details', function (Blueprint $table) {
            // $table->id();
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->Json('eligibility_status')->nullable();  // 0: Not Eligible, 1: Eligible
            $table->string('education_level')->nullable();
            $table->json('personal_info')->nullable();  // Store personal information as a JSON object
            $table->json('contact_info')->nullable();   // Store contact information as a JSON object
            $table->json('spouse_info')->nullable();
            $table->integer('children_info')->nullable();
            $table->timestamps();
        });
        DB::statement('ALTER TABLE applicant_details AUTO_INCREMENT = 500000000;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_details');
    }
};
