<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'recruiter', 'hiring_manager', 'viewer']);
    }

    public function view(User $user, Application $application): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'recruiter']);
    }

    public function update(User $user, Application $application): bool
    {
        return $user->hasAnyRole(['admin', 'recruiter', 'hiring_manager']);
    }

    public function delete(User $user, Application $application): bool
    {
        return $user->hasRole('admin');
    }
}
