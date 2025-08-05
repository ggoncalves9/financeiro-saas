<?php

namespace App\Policies;

use App\Models\Revenue;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RevenuePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view revenues');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Revenue $revenue): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
        // Permite que o usuário veja suas próprias receitas
        if ($revenue->user_id === $user->id) {
            return true;
        }
        if (!$user->can('view revenues')) {
            return false;
        }
        // Check if revenue belongs to user's tenant
        return $revenue->user->tenant_id === $user->tenant_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if (!$user->can('create revenues')) {
            return false;
        }

        // Check plan limits
        $tenant = $user->tenant;
        if (!$tenant) {
            return false;
        }

        $limits = $tenant->getPlanLimits();
        $currentCount = $user->revenues()->whereMonth('date', now()->month)->count();
        
        return $currentCount < $limits['revenues'];
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Revenue $revenue): bool
    {
        // Permite que o dono edite sempre
        if ($revenue->user_id === $user->id) {
            return true;
        }
        // Permite para quem tem permissão
        if ($user->can('edit revenues')) {
            // Para membros do mesmo tenant
            return $revenue->user->tenant_id === $user->tenant_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Revenue $revenue): bool
    {
        // Permite que o dono apague sempre
        if ($revenue->user_id === $user->id) {
            return true;
        }
        // Permite para quem tem permissão
        if ($user->can('delete revenues')) {
            // Só para admin/manager do mesmo tenant
            return $user->tenant_id === $revenue->user->tenant_id && 
                   $user->hasRole(['admin', 'manager']);
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Revenue $revenue): bool
    {
        return $this->delete($user, $revenue);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Revenue $revenue): bool
    {
        return $user->hasRole('admin');
    }
}
