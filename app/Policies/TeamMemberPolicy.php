<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TeamMember;

class TeamMemberPolicy
{
    /**
     * Permite que PJ visualize qualquer membro da equipe.
     */
    public function viewAny(User $user)
    {
        return $user->isPJ() || $user->isAdmin();
    }

    /**
     * Permite que PJ visualize membros da própria equipe.
     */
    public function view(User $user, TeamMember $teamMember)
    {
        return $user->isPJ() && $teamMember->company_user_id === $user->id;
    }

    /**
     * Permite que PJ crie membros na própria equipe.
     */
    public function create(User $user)
    {
        return $user->isPJ();
    }

    /**
     * Permite que PJ atualize membros da própria equipe.
     */
    public function update(User $user, TeamMember $teamMember)
    {
        return $user->isPJ() && $teamMember->company_user_id === $user->id;
    }

    /**
     * Permite que PJ exclua membros da própria equipe.
     */
    public function delete(User $user, TeamMember $teamMember)
    {
        return $user->isPJ() && $teamMember->company_user_id === $user->id;
    }
}
