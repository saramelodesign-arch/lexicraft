<?php

namespace App\Policies;

use App\Models\Concept;
use App\Models\User;

final class MediaPolicy
{
    public function viewLibrary(User $user): bool
    {
        return $user->canAccessAdmin();
    }

    public function manageForConcept(User $user, Concept $concept): bool
    {
        return $user->canAccessAdmin();
    }
}

