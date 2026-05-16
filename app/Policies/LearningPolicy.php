<?php

namespace App\Policies;

use App\Models\User;

final class LearningPolicy
{
    public function viewProgress(User $user, User $owner): bool
    {
        return $user->id === $owner->id || $user->canAccessAdmin();
    }
}

