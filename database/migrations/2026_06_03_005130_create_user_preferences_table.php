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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('defaultEventDurationMinutes')->default(60);
            $table->unsignedInteger('defaultMeetingDurationMinutes')->default(30);
            $table->time('preferredStartTime')->nullable();
            $table->time('preferredEndTime')->nullable();
            $table->unsignedInteger('bufferBetweenEventsMinutes')->default(0);
            $table->boolean('requireConfirmationBeforeCreate')->default(true);
            $table->boolean('autoCreateMeetingLink')->default(false);
            $table->boolean('autoCreateReminder')->default(false);
            $table->timestamp('createdAt')->nullable();
            $table->timestamp('updatedAt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
