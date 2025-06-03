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
            $table->string('product_number', 4)->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });
    } /*$table->foreignId('user-id')->constrained();*/

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
