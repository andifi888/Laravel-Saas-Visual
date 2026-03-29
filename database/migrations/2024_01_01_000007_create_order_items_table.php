<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->uuid('uuid')->unique();
            $table->string('product_name');
            $table->string('product_sku');
            $table->decimal('price', 12, 2);
            $table->decimal('cost', 12, 2)->default(0);
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('profit', 12, 2)->default(0);
            $table->timestamps();
            
            $table->index(['tenant_id', 'order_id']);
            $table->index(['tenant_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
