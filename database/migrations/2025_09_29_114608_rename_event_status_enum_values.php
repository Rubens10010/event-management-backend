<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TYPE event_status RENAME VALUE 'WAITING' TO 'ACCESSING'");
        DB::statement("ALTER TYPE event_status RENAME VALUE 'ONGOING' TO 'CLOSED'");
        DB::statement("ALTER TABLE events DROP CONSTRAINT IF EXISTS events_status_check");
    }

    public function down()
    {
        // Rollback (reverse rename)
        DB::statement("ALTER TYPE event_status RENAME VALUE 'ACCESSING' TO 'WAITING'");
        DB::statement("ALTER TYPE event_status RENAME VALUE 'CLOSED' TO 'ONGOING'");
    }
};
