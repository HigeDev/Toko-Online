<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create([
            'name' => 'view users'
        ]);
        Permission::create([
            'name' => 'edit users'
        ]);
        Permission::create([
            'name' => 'delete users'
        ]);
        Permission::create([
            'name' => 'create users'
        ]);

        Permission::create([
            'name' => 'view roles'
        ]);
        Permission::create([
            'name' => 'edit roles'
        ]);
        Permission::create([
            'name' => 'delete roles'
        ]);
        Permission::create([
            'name' => 'create roles'
        ]);

        Permission::create([
            'name' => 'view permissions'
        ]);
        Permission::create([
            'name' => 'edit permissions'
        ]);
        Permission::create([
            'name' => 'delete permissions'
        ]);
        Permission::create([
            'name' => 'create permissions'
        ]);

        Permission::create([
            'name' => 'view articles'
        ]);
        Permission::create([
            'name' => 'edit articles'
        ]);
        Permission::create([
            'name' => 'delete articles'
        ]);
        Permission::create([
            'name' => 'create articles'
        ]);
    }
}
