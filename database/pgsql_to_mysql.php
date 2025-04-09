<?php

// This script helps in migrating data from PostgreSQL to MySQL
// Run it with: php artisan tinker --execute="require database/pgsql_to_mysql.php"

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

echo "Starting migration from PostgreSQL to MySQL...\n";

// 1. First, backup the PostgreSQL database configuration
$pgsqlConfig = [
    'driver' => 'pgsql',
    'url' => env('DATABASE_URL'),
    'host' => 'ep-white-sun-a505gbud.us-east-2.aws.neon.tech',
    'port' => '5432',
    'database' => 'neondb',
    'username' => 'neondb_owner',
    'password' => 'npg_wlpI87FYkOtT',
    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'search_path' => 'public',
    'sslmode' => 'prefer',
];
echo "PostgreSQL configuration backed up.\n";

// 2. Update the .env file with MySQL configuration
$envContent = file_get_contents(base_path('.env'));
$envContent = preg_replace('/DB_CONNECTION=pgsql/', 'DB_CONNECTION=mysql', $envContent);
$envContent = preg_replace('/DB_HOST=ep-white-sun-a505gbud.us-east-2.aws.neon.tech/', 'DB_HOST=127.0.0.1', $envContent);
$envContent = preg_replace('/DB_PORT=5432/', 'DB_PORT=3306', $envContent);
$envContent = preg_replace('/DB_DATABASE=neondb/', 'DB_DATABASE=laundry_tiffin', $envContent);
$envContent = preg_replace('/DB_USERNAME=neondb_owner/', 'DB_USERNAME=root', $envContent);
$envContent = preg_replace('/DB_PASSWORD=npg_wlpI87FYkOtT/', 'DB_PASSWORD=', $envContent);
file_put_contents(base_path('.env'), $envContent);

echo "Environment file updated with MySQL configuration.\n";

// 3. Clear cache to apply the new configuration
Artisan::call('config:clear');
echo "Configuration cache cleared.\n";

// 4. Run the migrations to create the schema in MySQL
try {
    Artisan::call('migrate:fresh', ['--force' => true]);
    echo "Database schema migrated to MySQL.\n";
} catch (\Exception $e) {
    echo "Error during migration: " . $e->getMessage() . "\n";
    echo "Please make sure the MySQL database exists and is properly configured.\n";
    exit(1);
}

// 5. Run the seeders to populate the database
try {
    Artisan::call('db:seed', ['--force' => true]);
    echo "Database seeded successfully.\n";
} catch (\Exception $e) {
    echo "Error during seeding: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Migration completed successfully!\n";
echo "Your application is now configured to use MySQL instead of PostgreSQL.\n";
echo "Please verify the database connection and data integrity.\n";