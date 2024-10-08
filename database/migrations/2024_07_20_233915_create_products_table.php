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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('description');
            $table->string('brand')->index();
            $table->bigInteger('price');
            $table->string('stock');
            $table->string('image');
            $table->string('weight')->nullable();
            $table->string('measuring_unit')->nullable();
            $table->string('protein')->nullable();
            $table->string('creatine')->nullable();
            $table->unsignedBigInteger('sales_count')->default(0)->index();
            $table->unsignedBigInteger('view_count')->default(0)->index();
            $table->timestamp('expiration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
