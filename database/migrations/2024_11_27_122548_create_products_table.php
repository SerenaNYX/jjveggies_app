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
            
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->string('details')->nullable();
            $table->float('price');
            $table->string('category')->nullable();
            $table->text('description')->nullable();
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
