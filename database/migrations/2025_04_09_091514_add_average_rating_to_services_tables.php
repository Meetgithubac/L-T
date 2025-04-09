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
        Schema::table('laundry_services', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->nullable()->after('is_available');
            $table->integer('reviews_count')->unsigned()->default(0)->after('average_rating');
        });
        
        Schema::table('tiffin_services', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->nullable()->after('is_available');
            $table->integer('reviews_count')->unsigned()->default(0)->after('average_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laundry_services', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'reviews_count']);
        });
        
        Schema::table('tiffin_services', function (Blueprint $table) {
            $table->dropColumn(['average_rating', 'reviews_count']);
        });
    }
};