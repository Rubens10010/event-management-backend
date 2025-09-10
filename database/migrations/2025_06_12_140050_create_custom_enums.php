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
        DB::statement("CREATE TYPE event_status AS ENUM ('PENDING', 'REGISTERING', 'WAITING', 'ONGOING', 'FINISHED');");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TYPE IF EXISTS event_status;");
    }
};
