<?php

namespace App\Actions;

use App\Models\User;

class UpdateUser
{
    public function handle(User $user, array $validatedData): void
    {
        $user->update($validatedData);

        $user->permissions()->detach();

        if(isset($validatedData['permissions'])) {
            $user->permissions()->attach($validatedData['permissions']);
        }
    }
}
