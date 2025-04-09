<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        $permissionRegistrar = app(\Spatie\Permission\PermissionRegistrar::class);
        $permissionRegistrar->forgetCachedPermissions();

        // Create permissions for laundry services
        Permission::create(['name' => 'create laundry services']);
        Permission::create(['name' => 'edit laundry services']);
        Permission::create(['name' => 'delete laundry services']);
        Permission::create(['name' => 'view laundry services']);
        
        // Create permissions for tiffin services
        Permission::create(['name' => 'create tiffin services']);
        Permission::create(['name' => 'edit tiffin services']);
        Permission::create(['name' => 'delete tiffin services']);
        Permission::create(['name' => 'view tiffin services']);
        
        // Create permissions for orders
        Permission::create(['name' => 'create orders']);
        Permission::create(['name' => 'edit orders']);
        Permission::create(['name' => 'delete orders']);
        Permission::create(['name' => 'view orders']);
        Permission::create(['name' => 'process orders']);
        Permission::create(['name' => 'assign orders']);
        Permission::create(['name' => 'cancel orders']);
        
        // Create permissions for users
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'view users']);
        
        // Create roles and assign permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
        
        $customerRole = Role::create(['name' => 'customer']);
        $customerRole->givePermissionTo([
            'view laundry services',
            'view tiffin services',
            'create orders',
            'view orders',
            'cancel orders'
        ]);
        
        $serviceProviderRole = Role::create(['name' => 'service_provider']);
        $serviceProviderRole->givePermissionTo([
            'create laundry services',
            'edit laundry services',
            'view laundry services',
            'create tiffin services',
            'edit tiffin services',
            'view tiffin services',
            'view orders',
            'process orders',
        ]);
        
        $laundryProviderRole = Role::create(['name' => 'laundry_provider']);
        $laundryProviderRole->givePermissionTo([
            'create laundry services',
            'edit laundry services',
            'view laundry services',
            'view orders',
            'process orders',
        ]);
        
        $tiffinProviderRole = Role::create(['name' => 'tiffin_provider']);
        $tiffinProviderRole->givePermissionTo([
            'create tiffin services',
            'edit tiffin services',
            'view tiffin services',
            'view orders',
            'process orders',
        ]);
    }
}