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
        Schema::create('photo_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_detail_id')->constrained()->onDelete('cascade');
            $table->string('photo_owner')->nullable();
            $table->string('ephoto_link')->nullable();
            $table->string('image_url')->nullable();
            $table->string('originalFileName')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_details');
    }
};
