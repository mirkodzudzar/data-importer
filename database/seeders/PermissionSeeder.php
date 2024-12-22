<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $permissions = $this->preparePermissionsData($now);

        Permission::insert($permissions);
    }

    public function preparePermissionsData($now): array
    {
        return [
            [
                'name' => 'user-management',
                'label' => 'User Management',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'import-orders',
                'label' => 'Import Orders',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'import-products',
                'label' => 'Import Products',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];
    }
}
