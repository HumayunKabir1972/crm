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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('website')->nullable();

            // Lead qualification
            $table->enum('status', ['new', 'contacted', 'qualified', 'unqualified', 'lost', 'converted'])->default('new');
            $table->enum('stage', ['awareness', 'interest', 'consideration', 'intent', 'evaluation', 'purchase'])->default('awareness');
            $table->integer('lead_score')->default(0);
            $table->enum('quality', ['hot', 'warm', 'cold'])->default('cold');

            // Business details
            $table->string('industry')->nullable();
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->string('budget_range')->nullable();
            $table->date('expected_close_date')->nullable();

            // Source tracking
            $table->string('source')->nullable(); // Campaign, Website, Referral, etc.
            $table->string('campaign')->nullable();
            $table->string('medium')->nullable();
            $table->string('referring_url')->nullable();

            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            // Contact details
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();

            // Interaction
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('last_contacted_at')->nullable();
            $table->dateTime('next_followup_at')->nullable();

            // Conversion
            $table->foreignId('converted_customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->dateTime('converted_at')->nullable();

            $table->json('custom_fields')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('email');
            $table->index('phone');
            $table->index('status');
            $table->index('stage');
            $table->index('lead_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
