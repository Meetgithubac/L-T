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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->morphs('reviewable'); // For polymorphic relationship to LaundryService or TiffinService
            $table->integer('rating')->comment('1-5 stars');
            $table->text('comment');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            
            // Add index for better performance
            $table->index(['reviewable_type', 'reviewable_id']);
        });
        
        // Add average_rating column to laundry_services
        Schema::table('laundry_services', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->default(0)->after('is_active');
        });
        
        // Add average_rating column to tiffin_services
        Schema::table('tiffin_services', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->default(0)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove average_rating from laundry_services
        Schema::table('laundry_services', function (Blueprint $table) {
            $table->dropColumn('average_rating');
        });
        
        // Remove average_rating from tiffin_services
        Schema::table('tiffin_services', function (Blueprint $table) {
            $table->dropColumn('average_rating');
        });
        
        // Drop the reviews table
        Schema::dropIfExists('reviews');
    }
};