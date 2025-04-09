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
        Schema::create('laundry_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('service_type', ['wash', 'dry_clean', 'iron', 'fold', 'package_deal']);
            $table->enum('unit', ['per_kg', 'per_piece', 'per_bundle']);
            $table->integer('estimated_hours')->default(24);
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
        Schema::dropIfExists('laundry_services');
    }
};
