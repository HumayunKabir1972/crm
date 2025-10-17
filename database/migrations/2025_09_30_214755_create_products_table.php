<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('sku')->unique()->nullable();
            $table->string('category')->nullable();

            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');

            $table->integer('stock_quantity')->default(0);
            $table->integer('reorder_level')->default(0);
            $table->boolean('track_inventory')->default(true);

            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active');
            $table->enum('type', ['product', 'service'])->default('product');

            $table->string('unit')->nullable(); // piece, kg, hour, etc.
            $table->decimal('tax_rate', 5, 2)->default(0);

            $table->string('image')->nullable();
            $table->json('images')->nullable();

            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index('category');
            $table->index('status');
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
