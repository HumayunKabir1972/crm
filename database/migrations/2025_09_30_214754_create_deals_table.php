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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('deal_code')->unique();
            $table->string('title');
            $table->text('description')->nullable();

            // Relations
            $table->foreignId('customer_id')->nullable()->constrained('customers')->cascadeOnDelete();
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();

            // Deal details
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->enum('stage', ['prospecting', 'qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost'])->default('prospecting');
            $table->integer('probability')->default(0); // 0-100
            $table->date('expected_close_date')->nullable();
            $table->date('actual_close_date')->nullable();

            // Status
            $table->enum('status', ['open', 'won', 'lost', 'abandoned'])->default('open');
            $table->string('lost_reason')->nullable();

            // Assignment
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // Pipeline
            $table->string('pipeline')->default('sales'); // sales, renewal, upsell
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');

            // Source
            $table->string('source')->nullable();
            $table->string('campaign')->nullable();

            // Additional info
            $table->text('notes')->nullable();
            $table->json('products')->nullable(); // Array of product IDs and quantities
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);

            // Forecasting
            $table->decimal('weighted_amount', 15, 2)->default(0); // amount * probability
            $table->integer('days_to_close')->nullable();

            $table->json('custom_fields')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('stage');
            $table->index('status');
            $table->index('expected_close_date');
            $table->index('assigned_to');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
