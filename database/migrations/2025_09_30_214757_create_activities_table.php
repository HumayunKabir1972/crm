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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // email, call, meeting, note, status_change, etc.
            $table->text('description');

            $table->morphs('activityable'); // Customer, Lead, Deal, etc.

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->dateTime('activity_date');
            $table->integer('duration')->nullable(); // in minutes

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index('type');
            $table->index('activity_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
