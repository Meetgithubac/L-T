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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('order_type', ['laundry', 'tiffin', 'mixed']);
            $table->enum('status', ['pending', 'processing', 'completed', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('total_amount', 10, 2);
            $table->text('delivery_address')->nullable();
            $table->string('delivery_phone')->nullable();
            $table->dateTime('delivery_time')->nullable();
            $table->text('special_instructions')->nullable();
            $table->string('payment_method')->default('cash');
            $table->boolean('is_paid')->default(false);
            $table->text('cancellation_reason')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
