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
        Schema::create('curriculum_vitae_experience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_vitae_id')->index()->references('id')->on('curricula_vitae')->cascadeOnDelete();
            $table->foreignId('experience_id')->index()->references('id')->on('experiences')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['curriculum_vitae_id', 'experience_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curriculum_vitae_experience');
    }
};
