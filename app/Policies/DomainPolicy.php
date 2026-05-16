<?php

namespace App\Policies;

use App\Models\Domain;
use App\Models\User;

final class DomainPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessAdmin();
    }

    public function view(User $user, Domain $domain): bool
    {
        return $user->canAccessAdmin();
    }

    public function create(User $user): bool
    {
        return $user->canAccessAdmin();
    }

    public function update(User $user, Domain $domain): bool
    {
        return $user->canAccessAdmin();
    }

    public function delete(User $user, Domain $domain): bool
    {
        return $user->canAccessAdmin();
    }
}

