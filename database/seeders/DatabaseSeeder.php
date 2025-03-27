<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Modules\Shop\Database\Seeders\ShopDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if ($this->command->confirm('Do you want to refresh migration before seeding, it will clear all old data ?')) {
            $this->command->call('migrate:fresh');
            $this->command->info('Data cleared, starting from blank database');
        }

        $this->call([UserSeeder::class, PermissionSeeder::class, RoleSeeder::class, ArticleSeeder::class]);
        $this->command->info('Sample user seeded');

        if ($this->command->confirm('Do you want seed some sample product ?')) {
            $this->call([ShopDatabaseSeeder::class]);
        }
    }
}
