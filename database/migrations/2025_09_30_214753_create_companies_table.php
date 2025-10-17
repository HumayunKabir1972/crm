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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unique();
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            $table->string('industry')->nullable();
            $table->integer('employees')->nullable();
            $table->decimal('annual_revenue', 15, 2)->nullable();
            $table->string('tax_id')->nullable();

            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip')->nullable();

            $table->string('linkedin')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();

            $table->foreignId('parent_company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();

            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'prospect'])->default('active');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('companies');
    }
};