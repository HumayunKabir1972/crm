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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('body');
            $table->string('from_email');
            $table->string('to_email');
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();

            $table->morphs('emailable');

            $table->enum('status', ['draft', 'sent', 'failed', 'bounced'])->default('draft');
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('opened_at')->nullable();

            $table->json('attachments')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emails');
        Schema::dropIfExists('notes');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('products');
    }
};
