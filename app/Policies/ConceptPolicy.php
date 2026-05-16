<?php

namespace App\Policies;

use App\Models\Concept;
use App\Models\User;

final class ConceptPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canAccessAdmin();
    }

    public function view(User $user, Concept $concept): bool
    {
        return $user->canAccessAdmin();
    }

    public function create(User $user): bool
    {
        return $user->canAccessAdmin();
    }

    public function update(User $user, Concept $concept): bool
    {
        return $user->canAccessAdmin();
    }

    public function delete(User $user, Concept $concept): bool
    {
        return $user->canAccessAdmin();
    }

    public function manageTranslations(User $user, Concept $concept): bool
    {
        return $user->canAccessAdmin();
    }

    public function manageRelations(User $user, Concept $concept): bool
    {
        return $user->canAccessAdmin();
    }

    public function manageMedia(User $user, Concept $concept): bool
    {
        return $user->canAccessAdmin();
    }
}

