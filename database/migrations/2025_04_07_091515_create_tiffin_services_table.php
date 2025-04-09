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
        Schema::create('tiffin_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'combo']);
            $table->enum('cuisine', ['indian', 'chinese', 'italian', 'thai', 'mexican', 'continental', 'mixed']);
            $table->boolean('is_vegetarian')->default(false);
            $table->json('menu_items')->nullable();
            $table->boolean('is_available')->default(true);
            $table->foreignId('provider_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiffin_services');
    }
};
