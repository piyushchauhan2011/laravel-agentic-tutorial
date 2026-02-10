<?php

namespace App\Policies;

use App\Models\Candidate;
use App\Models\User;

class CandidatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'recruiter', 'hiring_manager', 'viewer']);
    }

    public function view(User $user, Candidate $candidate): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'recruiter']);
    }

    public function update(User $user, Candidate $candidate): bool
    {
        return $user->hasAnyRole(['admin', 'recruiter', 'hiring_manager']);
    }

    public function delete(User $user, Candidate $candidate): bool
    {
        return $user->hasRole('admin');
    }
}
