<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Tenant extends Model
{
    use HasFactory, Billable;

    protected $fillable = [
        'name',
        'slug',
        'domain',
        'stripe_id',
        'plan',
        'plan_expires_at',
        'trial_ends_at',
        'is_active',
        'settings',
        'custom_domain',
        'logo_url',
        'primary_color',
        'secondary_color',
        'timezone',
        'locale',
        'currency',
        'date_format',
        'created_by',
    ];

    protected $casts = [
        'plan_expires_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Get the users for the tenant.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the creator of the tenant.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all revenues for this tenant.
     */
    public function revenues()
    {
        return $this->hasManyThrough(Revenue::class, User::class);
    }

    /**
     * Get all expenses for this tenant.
     */
    public function expenses()
    {
        return $this->hasManyThrough(Expense::class, User::class);
    }

    /**
     * Get all goals for this tenant.
     */
    public function goals()
    {
        return $this->hasManyThrough(Goal::class, User::class);
    }

    /**
     * Check if tenant is on trial.
     */
    public function onTrial()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if tenant subscription is active.
     */
    public function subscriptionActive()
    {
        if ($this->plan === 'free') {
            return true;
        }

        return $this->subscribed('default');
    }

    /**
     * Get plan limits.
     */
    public function getPlanLimits()
    {
        $limits = [
            'free' => [
                'users' => 1,
                'transactions' => 100,
                'accounts' => 2,
                'goals' => 3,
                'reports' => false,
                'api_access' => false,
                'ai_features' => false,
                'priority_support' => false,
            ],
            'pro_pf' => [
                'users' => 1,
                'transactions' => 1000,
                'accounts' => 10,
                'goals' => 20,
                'reports' => true,
                'api_access' => false,
                'ai_features' => true,
                'priority_support' => false,
            ],
            'empresarial' => [
                'users' => 10,
                'transactions' => 5000,
                'accounts' => 50,
                'goals' => 100,
                'reports' => true,
                'api_access' => true,
                'ai_features' => true,
                'priority_support' => false,
            ],
            'premium_pj' => [
                'users' => -1, // unlimited
                'transactions' => -1,
                'accounts' => -1,
                'goals' => -1,
                'reports' => true,
                'api_access' => true,
                'ai_features' => true,
                'priority_support' => true,
            ],
        ];

        return $limits[$this->plan] ?? $limits['free'];
    }

    /**
     * Check if tenant can perform action based on plan limits.
     */
    public function canPerformAction($action, $currentCount = 0)
    {
        $limits = $this->getPlanLimits();
        
        if (!isset($limits[$action])) {
            return true;
        }

        $limit = $limits[$action];
        
        if ($limit === -1) {
            return true; // unlimited
        }

        if (is_bool($limit)) {
            return $limit;
        }

        return $currentCount < $limit;
    }
}
