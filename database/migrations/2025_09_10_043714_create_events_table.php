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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained('organizations')->onDelete('cascade');
            $table->text('name');
            $table->text('description')->nullable();
            $table->text('location');
            $table->enum('status', ['PENDING', 'REGISTERING', 'WAITING', 'ONGOING', 'FINISHED']);
            $table->integer('capacity');
            $table->integer('max_invitees')->default(0);
            $table->binary('banner')->nullable();
            $table->dateTime('starts_at');
            $table->dateTime('finishes_at');
            $table->timestamps();
        });

        // Alter columns to use PostgreSQL ENUMs
        DB::statement("ALTER TABLE events ALTER COLUMN status TYPE event_status USING status::event_status;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
