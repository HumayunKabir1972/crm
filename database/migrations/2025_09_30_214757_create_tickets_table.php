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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->nullOnDelete();

            $table->string('subject');
            $table->text('description');

            $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('open');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('type', ['question', 'incident', 'problem', 'feature_request', 'refund'])->default('question');

            $table->string('category')->nullable();
            $table->string('channel')->default('web'); // email, phone, chat, web

            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->dateTime('first_response_at')->nullable();
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('closed_at')->nullable();

            $table->integer('response_time')->nullable(); // in minutes
            $table->integer('resolution_time')->nullable(); // in minutes

            $table->text('resolution_notes')->nullable();
            $table->integer('satisfaction_rating')->nullable(); // 1-5
            $table->text('satisfaction_comment')->nullable();

            $table->json('tags')->nullable();
            $table->json('attachments')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('priority');
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
