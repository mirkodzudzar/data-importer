<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe 1',
            'email' => 'john1@doe.com',
        ]);

        $user->permissions()->attach(Permission::pluck('id'));

        User::factory()->create([
            'name' => 'John Doe 2',
            'email' => 'john2@doe.com',
        ]);
    }
}
