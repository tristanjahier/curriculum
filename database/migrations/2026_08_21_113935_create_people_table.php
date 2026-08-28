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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->dateTime('birth_datetime');
            $table->string('birth_timezone');
            $table->string('residence')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });

        // Add an index to optimize ordering by the absolute birth timestamp.
        // It also serves as a constraint to validate birth_timezone.
        DB::statement('CREATE INDEX people_born_at_index ON people ((birth_datetime AT TIME ZONE birth_timezone))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
