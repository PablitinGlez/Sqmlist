<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserObserver
{
    public function created(User $user): void
    {
        if (Role::where('name', 'user')->exists()) {
            $user->assignRole('user');
        }
    }

    public function updated(User $user): void
    {
        
    }

    public function deleted(User $user): void
    {
        
    }

    public function restored(User $user): void
    {
       
    }

    public function forceDeleted(User $user): void
    {
       
    }
}
