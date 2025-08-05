<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_user_id',
        'member_user_id',
        'role',
        'permissions',
        'department',
        'position',
        'salary',
        'hire_date',
        'is_active',
        'access_level', // full, limited, read_only
        'can_approve_expenses',
        'expense_limit',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'salary' => 'decimal:2',
        'expense_limit' => 'decimal:2',
        'is_active' => 'boolean',
        'can_approve_expenses' => 'boolean',
        'permissions' => 'array',
    ];

    /**
     * Get the company user (PJ owner).
     */
    public function company()
    {
        return $this->belongsTo(User::class, 'company_user_id');
    }

    /**
     * Get the member user.
     */
    public function member()
    {
        return $this->belongsTo(User::class, 'member_user_id');
    }

    /**
     * Scope for active team members.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for members who can approve expenses.
     */
    public function scopeCanApproveExpenses($query)
    {
        return $query->where('can_approve_expenses', true);
    }

    /**
     * Check if member has permission.
     */
    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return false;
        }

        return in_array($permission, $this->permissions);
    }

    /**
     * Add permission to member.
     */
    public function addPermission($permission)
    {
        $permissions = $this->permissions ?? [];
        
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
            $this->update(['permissions' => $permissions]);
        }

        return $this;
    }

    /**
     * Remove permission from member.
     */
    public function removePermission($permission)
    {
        $permissions = $this->permissions ?? [];
        
        if (($key = array_search($permission, $permissions)) !== false) {
            unset($permissions[$key]);
            $this->update(['permissions' => array_values($permissions)]);
        }

        return $this;
    }

    /**
     * Check if member can approve expense amount.
     */
    public function canApproveAmount($amount)
    {
        if (!$this->can_approve_expenses) {
            return false;
        }

        if (!$this->expense_limit) {
            return true; // no limit
        }

        return $amount <= $this->expense_limit;
    }

    /**
     * Get formatted salary.
     */
    public function getFormattedSalaryAttribute()
    {
        return 'R$ ' . number_format($this->salary ?? 0, 2, ',', '.');
    }

    /**
     * Get formatted expense limit.
     */
    public function getFormattedExpenseLimitAttribute()
    {
        return 'R$ ' . number_format($this->expense_limit ?? 0, 2, ',', '.');
    }

    /**
     * Deactivate team member.
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Activate team member.
     */
    public function activate()
    {
        $this->update(['is_active' => true]);
    }
}
