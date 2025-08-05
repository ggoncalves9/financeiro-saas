<?php

namespace App\Policies;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Allow all authenticated users
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Expense $expense): bool
    {
        return $expense->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Allow all authenticated users
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Expense $expense): bool
    {
        return $expense->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Expense $expense): bool
    {
        return $expense->user_id === $user->id;
    }

    /**
     * Determine whether the user can pay the expense.
     */
    public function pay(User $user, Expense $expense): bool
    {
        return $expense->user_id === $user->id && $expense->status === 'pending';
    }

    /**
     * Determine whether the user can approve the expense.
     */
    public function approve(User $user, Expense $expense): bool
    {
        // Only for business expenses or team members
        return $user->isPJ() && ($expense->user_id === $user->id || $user->isAdmin());
    }
}
