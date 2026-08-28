<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // "+1s" to allow for potential clock skew between the PHP and database servers.
        DB::statement("
            ALTER TABLE curricula_vitae ADD CONSTRAINT curricula_vitae_default_is_published CHECK (
                NOT is_default
                OR (published_at IS NOT NULL AND published_at <= (now() AT TIME ZONE 'UTC') + INTERVAL '1 second')
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE curricula_vitae DROP CONSTRAINT curricula_vitae_default_is_published');
    }
};
