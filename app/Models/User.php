<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'cnpj',
        'type', // 'pf' or 'pj'
        'tenant_id',
        'phone',
        'birth_date',
        'address',
        'city',
        'state',
        'zip_code',
        'company_name',
        'company_size',
        'is_active',
        'is_admin',
        'last_login_at',
        'email_verified_at',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
        'is_active' => 'boolean',
        'is_admin' => 'boolean',
        'birth_date' => 'date',
    ];

    /**
     * Get the tenant that owns the user.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the revenues for the user.
     */
    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    /**
     * Get the expenses for the user.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the goals for the user.
     */
    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    /**
     * Get the accounts for the user.
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    /**
     * Get the subscription for the user.
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * Get all subscriptions for the user.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get all payments for the user.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the current plan for the user.
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the team members if user is PJ.
     */
    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'company_user_id');
    }

    /**
     * Get the companies where user is a team member.
     */
    public function memberOf()
    {
        return $this->hasMany(TeamMember::class, 'member_user_id');
    }

    /**
     * Check if user is Person Física (PF).
     */
    public function isPF()
    {
        return $this->type === 'pf';
    }

    /**
     * Check if user is Person Jurídica (PJ).
     */
    public function isPJ()
    {
        return $this->type === 'pj';
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Get total balance.
     */
    public function getTotalBalance()
    {
        $totalRevenues = $this->revenues()->sum('amount');
        $totalExpenses = $this->expenses()->sum('amount');
        
        return $totalRevenues - $totalExpenses;
    }

    /**
     * Get monthly balance.
     */
    public function getMonthlyBalance($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $monthlyRevenues = $this->revenues()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $monthlyExpenses = $this->expenses()
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        return $monthlyRevenues - $monthlyExpenses;
    }

    /**
     * Revoke all tokens and sessions (Global Logout).
     */
    public function revokeAllTokensAndSessions()
    {
        // Revoke all Sanctum tokens
        $this->tokens()->delete();
        
        // Force logout from all sessions
        session()->invalidate();
        session()->regenerateToken();
    }
}
