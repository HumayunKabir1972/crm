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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();

            // Task details
            $table->enum('type', ['call', 'meeting', 'email', 'todo', 'deadline', 'follow_up'])->default('todo');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            // Dates
            $table->dateTime('start_date')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->integer('duration')->nullable(); // in minutes

            // Assignment
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            // Relations (polymorphic)
            $table->morphs('taskable'); // Can be attached to customer, lead, deal, etc.

            // Reminders
            $table->dateTime('reminder_at')->nullable();
            $table->boolean('reminder_sent')->default(false);

            // Progress
            $table->integer('progress')->default(0); // 0-100

            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->json('custom_fields')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('priority');
            $table->index('due_date');
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
