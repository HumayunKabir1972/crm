<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('company_name')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->integer('employees')->nullable();
            $table->enum('customer_type', ['individual', 'business'])->default('individual');
            $table->enum('status', ['active', 'inactive', 'prospect'])->default('active');

            // Address fields
            $table->text('billing_address')->nullable();
            $table->string('billing_city')->nullable();
            $table->string('billing_state')->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_zip')->nullable();

            $table->text('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_country')->nullable();
            $table->string('shipping_zip')->nullable();

            // Social media
            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();

            // Business details
            $table->string('tax_id')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('language', 5)->default('en');
            $table->string('timezone')->default('UTC');

            // CRM fields
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->decimal('lifetime_value', 15, 2)->default(0);
            $table->date('last_contact_date')->nullable();
            $table->date('next_contact_date')->nullable();

            // Metadata
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            $table->string('source')->nullable(); // Website, Referral, Campaign, etc.
            $table->string('avatar')->nullable();

            $table->softDeletes();
            $table->timestamps();

            // Indexes
            $table->index('email');
            $table->index('phone');
            $table->index('company_name');
            $table->index('status');
            $table->index('assigned_to');
        }); 
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};