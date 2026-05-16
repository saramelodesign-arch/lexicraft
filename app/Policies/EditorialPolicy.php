<?php

namespace App\Policies;

use App\Models\User;

final class EditorialPolicy
{
    public function viewDashboard(User $user): bool
    {
        return $user->canAccessAdmin();
    }

    public function viewSeoOverview(User $user): bool
    {
        return $user->canAccessAdmin();
    }

    public function manageActions(User $user): bool
    {
        return $user->canAccessAdmin();
    }
}

