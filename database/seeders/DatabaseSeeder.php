<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RoleSeeder::class);
        
        // Create an admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '1234567890',
            'address' => '123 Admin Street, Admin City',
        ]);
        $admin->assignRole('admin');
        
        // Create a customer user
        $customer = User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'phone' => '0987654321',
            'address' => '456 Customer Avenue, Customer Town',
        ]);
        $customer->assignRole('customer');
        
        // Create a service provider user
        $provider = User::factory()->create([
            'name' => 'Service Provider',
            'email' => 'provider@example.com',
            'phone' => '1122334455',
            'address' => '789 Provider Road, Provider City',
        ]);
        $provider->assignRole('service_provider');
        
        // Create a laundry provider user
        $laundryProvider = User::factory()->create([
            'name' => 'Laundry Provider',
            'email' => 'laundry@example.com',
            'phone' => '6677889900',
            'address' => '101 Laundry Lane, Laundry City',
        ]);
        $laundryProvider->assignRole('laundry_provider');
        
        // Create a tiffin provider user
        $tiffinProvider = User::factory()->create([
            'name' => 'Tiffin Provider',
            'email' => 'tiffin@example.com',
            'phone' => '5544332211',
            'address' => '202 Tiffin Street, Tiffin Town',
        ]);
        $tiffinProvider->assignRole('tiffin_provider');
        
        // Seed services after users
        $this->call(ServiceSeeder::class);
    }
}
