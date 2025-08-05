<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrivacyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view their own data
     */
    public function viewOwnData(User $user): bool
    {
        return true; // Users can always view their own data
    }

    /**
     * Determine if user can export their data
     */
    public function exportData(User $user): bool
    {
        return true; // LGPD/GDPR right to data portability
    }

    /**
     * Determine if user can delete their account
     */
    public function deleteAccount(User $user): bool
    {
        // Users can delete their own account unless they have active subscription
        if ($user->tenant && $user->tenant->subscribed()) {
            return false; // Must cancel subscription first
        }
        
        return true;
    }

    /**
     * Determine if user can anonymize their data
     */
    public function anonymizeData(User $user): bool
    {
        return $this->deleteAccount($user);
    }

    /**
     * Determine if admin can view user data
     */
    public function viewUserData(User $admin, User $user): bool
    {
        if (!$admin->is_admin) {
            return false;
        }

        // Log admin access for audit
        activity()
            ->performedOn($user)
            ->causedBy($admin)
            ->withProperties(['action' => 'data_access'])
            ->log('Admin accessed user data');

        return true;
    }

    /**
     * Determine if user can request data correction
     */
    public function requestCorrection(User $user): bool
    {
        return true; // LGPD/GDPR right to rectification
    }

    /**
     * Determine if user can object to data processing
     */
    public function objectToProcessing(User $user): bool
    {
        return true; // LGPD/GDPR right to object
    }
}
