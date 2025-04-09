<?php

namespace Database\Seeders;

use App\Models\LaundryService;
use App\Models\TiffinService;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get provider IDs - simplified approach to avoid query issues
        $users = User::all();
        
        $laundryProvider = null;
        $tiffinProvider = null;
        $serviceProvider = null;
        
        foreach ($users as $user) {
            if ($user->hasRole('laundry_provider')) {
                $laundryProvider = $user;
            }
            if ($user->hasRole('tiffin_provider')) {
                $tiffinProvider = $user;
            }
            if ($user->hasRole('service_provider')) {
                $serviceProvider = $user;
            }
        }
        
        // Seed laundry services for laundry provider
        if ($laundryProvider) {
            $regularWashing = new LaundryService([
                'name' => 'Regular Washing',
                'description' => 'Regular machine wash for everyday clothes',
                'price' => 12.99,
                'service_type' => 'wash',
                'unit' => 'per_kg',
                'estimated_hours' => 24,
                'is_available' => true,
                'provider_id' => $laundryProvider->id
            ]);
            $regularWashing->save();
            
            $premiumDryCleaning = new LaundryService([
                'name' => 'Premium Dry Cleaning',
                'description' => 'Professional dry cleaning for delicate garments',
                'price' => 25.99,
                'service_type' => 'dry_clean',
                'unit' => 'per_piece',
                'estimated_hours' => 48,
                'is_available' => true,
                'provider_id' => $laundryProvider->id
            ]);
            $premiumDryCleaning->save();
            
            $ironingService = new LaundryService([
                'name' => 'Ironing Service',
                'description' => 'Professional ironing for all types of clothes',
                'price' => 8.99,
                'service_type' => 'iron',
                'unit' => 'per_piece',
                'estimated_hours' => 12,
                'is_available' => true,
                'provider_id' => $laundryProvider->id
            ]);
            $ironingService->save();
        }
        
        // Seed laundry services for general service provider
        if ($serviceProvider) {
            $completePackage = new LaundryService([
                'name' => 'Complete Laundry Package',
                'description' => 'Wash, dry, iron, and fold your clothes',
                'price' => 29.99,
                'service_type' => 'package_deal',
                'unit' => 'per_kg',
                'estimated_hours' => 36,
                'is_available' => true,
                'provider_id' => $serviceProvider->id
            ]);
            $completePackage->save();
            
            $foldOnly = new LaundryService([
                'name' => 'Fold Only',
                'description' => 'Just folding service for pre-washed clothes',
                'price' => 5.99,
                'service_type' => 'fold',
                'unit' => 'per_kg',
                'estimated_hours' => 6,
                'is_available' => true,
                'provider_id' => $serviceProvider->id
            ]);
            $foldOnly->save();
        }
        
        // Seed tiffin services for tiffin provider
        if ($tiffinProvider) {
            $vegLunch = new TiffinService([
                'name' => 'Vegetarian Daily Lunch',
                'description' => 'Healthy vegetarian lunch with roti, rice, dal, and 2 vegetables',
                'price' => 9.99,
                'meal_type' => 'lunch',
                'cuisine' => 'indian',
                'is_vegetarian' => true,
                'menu_items' => ['Roti', 'Rice', 'Dal', 'Mixed Vegetables', 'Salad'],
                'is_available' => true,
                'provider_id' => $tiffinProvider->id
            ]);
            $vegLunch->save();
            
            $nonVegDinner = new TiffinService([
                'name' => 'Non-Veg Dinner Special',
                'description' => 'Delicious non-vegetarian dinner with chicken or mutton dish',
                'price' => 14.99,
                'meal_type' => 'dinner',
                'cuisine' => 'indian',
                'is_vegetarian' => false,
                'menu_items' => ['Roti', 'Rice', 'Chicken Curry/Mutton Curry', 'Vegetable Side', 'Dessert'],
                'is_available' => true,
                'provider_id' => $tiffinProvider->id
            ]);
            $nonVegDinner->save();
            
            $chineseLunch = new TiffinService([
                'name' => 'Chinese Lunch Box',
                'description' => 'Tasty Chinese style lunch with noodles or fried rice',
                'price' => 11.99,
                'meal_type' => 'lunch',
                'cuisine' => 'chinese',
                'is_vegetarian' => false,
                'menu_items' => ['Noodles/Fried Rice', 'Manchurian', 'Soup', 'Spring Roll', 'Sauce'],
                'is_available' => true,
                'provider_id' => $tiffinProvider->id
            ]);
            $chineseLunch->save();
        }
        
        // Seed tiffin services for general service provider
        if ($serviceProvider) {
            $weeklyMealPlan = new TiffinService([
                'name' => 'Weekly Meal Plan',
                'description' => 'Weekly subscription for 7 days of lunch and dinner',
                'price' => 79.99,
                'meal_type' => 'combo',
                'cuisine' => 'mixed',
                'is_vegetarian' => false,
                'menu_items' => ['Monday: Italian', 'Tuesday: Indian', 'Wednesday: Chinese', 'Thursday: Mexican', 'Friday: Continental', 'Weekend: Chef\'s Special'],
                'is_available' => true,
                'provider_id' => $serviceProvider->id
            ]);
            $weeklyMealPlan->save();
            
            $vegWeeklyPlan = new TiffinService([
                'name' => 'Vegetarian Weekly Plan',
                'description' => 'Weekly subscription for 7 days of vegetarian meals',
                'price' => 69.99,
                'meal_type' => 'combo',
                'cuisine' => 'mixed',
                'is_vegetarian' => true,
                'menu_items' => ['Monday: Italian Veg', 'Tuesday: Indian Veg', 'Wednesday: Chinese Veg', 'Thursday: Mexican Veg', 'Friday: Continental Veg', 'Weekend: Chef\'s Special Veg'],
                'is_available' => true,
                'provider_id' => $serviceProvider->id
            ]);
            $vegWeeklyPlan->save();
        }
    }
}