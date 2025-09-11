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
        DB::statement("CREATE TYPE person_type AS ENUM ('PARTICIPANT', 'INVITEE');");
        DB::statement("CREATE TYPE action AS ENUM ('ENTRY', 'EXIT');");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TYPE IF EXISTS event_status;");
        DB::statement("DROP TYPE IF EXISTS person_type;");
        DB::statement("DROP TYPE IF EXISTS action;");
    }
};
