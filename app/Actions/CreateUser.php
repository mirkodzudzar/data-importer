<?php

namespace App\Actions;

use App\Models\User;

class CreateUser
{
    public function handle(array $validatedData): void
    {
        $user = User::create($validatedData);

        if(isset($validatedData['permissions'])) {
            $user->permissions()->attach($validatedData['permissions']);
        }
    }
}
