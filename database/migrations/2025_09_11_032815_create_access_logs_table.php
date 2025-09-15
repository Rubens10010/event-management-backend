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
        Schema::create('access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('participant_id')->constrained('participants')->onDelete('cascade');
            $table->enum('person_type', ['PARTICIPANT', 'INVITEE']);
            $table->char('ndoc', 10);
            $table->foreignId('gatekeeper_id')->constrained('event_gatekeepers')->onDelete('cascade');
            $table->enum('action', ['ENTRY', 'EXIT']);
            $table->timestamps();
        });

        DB::statement("ALTER TABLE access_logs ALTER COLUMN person_type TYPE person_type USING person_type::person_type;");
        DB::statement("ALTER TABLE access_logs ALTER COLUMN action TYPE action USING action::action;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('access_logs');
    }
};
