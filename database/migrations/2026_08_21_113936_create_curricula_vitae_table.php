<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('curricula_vitae', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->foreignId('person_id')->index()->references('id')->on('people')->onDelete('cascade');
            $table->string('headline')->nullable();
            $table->text('summary')->nullable();
            $table->boolean('show_photo')->default(false);
            $table->boolean('show_age')->default(false);
            $table->boolean('show_residence')->default(false);
            $table->boolean('show_phone')->default(false);
            $table->boolean('show_email')->default(false);
            $table->boolean('is_default')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        // Add a constraint to have MAXIMUM one row where is_default = true.
        DB::statement('CREATE UNIQUE INDEX curricula_vitae_single_default ON curricula_vitae ((is_default)) WHERE is_default');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curricula_vitae');
    }
};
