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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('deal_id')->nullable()->constrained('deals')->nullOnDelete();

            $table->date('invoice_date');
            $table->date('due_date');
            $table->enum('status', ['draft', 'sent', 'viewed', 'partial', 'paid', 'overdue', 'cancelled'])->default('draft');

            // Amounts
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);

            $table->string('currency', 3)->default('USD');

            // Payment details
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->date('payment_date')->nullable();

            // Addresses
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();

            // Additional info
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->json('line_items')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('due_date');
            $table->index('customer_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
