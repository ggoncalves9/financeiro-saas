<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'target_amount',
        'current_amount',
        'target_date',
        'category',
        'type', // saving, investment, debt_payment, expense_reduction
        'status', // active, paused, completed, cancelled
        'priority', // low, medium, high
        'auto_save',
        'auto_save_amount',
        'auto_save_frequency', // daily, weekly, monthly
        'linked_account_id',
        'reminder_enabled',
        'reminder_frequency',
        'notes',
        'achievement_date',
    ];

    protected $casts = [
        'target_date' => 'date',
        'achievement_date' => 'date',
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'auto_save_amount' => 'decimal:2',
        'auto_save' => 'boolean',
        'reminder_enabled' => 'boolean',
    ];

    /**
     * Get the user that owns the goal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the linked account for auto-save.
     */
    public function linkedAccount()
    {
        return $this->belongsTo(Account::class, 'linked_account_id');
    }

    /**
     * Get goal transactions (contributions).
     */
    public function transactions()
    {
        return $this->hasMany(GoalTransaction::class);
    }

    /**
     * Scope for active goals.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for completed goals.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for overdue goals.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'active')
                    ->where('target_date', '<', now())
                    ->where('current_amount', '<', 'target_amount');
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) {
            return 0;
        }

        $percentage = ($this->current_amount / $this->target_amount) * 100;
        return min(100, round($percentage, 2));
    }

    /**
     * Calculate remaining amount.
     */
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->target_amount - $this->current_amount);
    }

    /**
     * Calculate days remaining.
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->target_date || $this->target_date->isPast()) {
            return 0;
        }

        return $this->target_date->diffInDays(now());
    }

    /**
     * Calculate required monthly savings.
     */
    public function getRequiredMonthlySavingsAttribute()
    {
        if (!$this->target_date || $this->target_date->isPast()) {
            return $this->remaining_amount;
        }

        $monthsRemaining = max(1, $this->target_date->diffInMonths(now()));
        return $this->remaining_amount / $monthsRemaining;
    }

    /**
     * Get formatted target amount.
     */
    public function getFormattedTargetAmountAttribute()
    {
        return 'R$ ' . number_format($this->target_amount, 2, ',', '.');
    }

    /**
     * Get formatted current amount.
     */
    public function getFormattedCurrentAmountAttribute()
    {
        return 'R$ ' . number_format($this->current_amount, 2, ',', '.');
    }

    /**
     * Get formatted remaining amount.
     */
    public function getFormattedRemainingAmountAttribute()
    {
        return 'R$ ' . number_format($this->remaining_amount, 2, ',', '.');
    }

    /**
     * Check if goal is achieved.
     */
    public function isAchieved()
    {
        return $this->current_amount >= $this->target_amount;
    }

    /**
     * Check if goal is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'active' && 
               $this->target_date && 
               $this->target_date->isPast() && 
               !$this->isAchieved();
    }

    /**
     * Add contribution to goal.
     */
    public function addContribution($amount, $description = null)
    {
        $this->transactions()->create([
            'amount' => $amount,
            'type' => 'contribution',
            'description' => $description,
            'date' => now(),
        ]);

        $this->increment('current_amount', $amount);

        // Check if goal is achieved
        if ($this->isAchieved() && $this->status === 'active') {
            $this->update([
                'status' => 'completed',
                'achievement_date' => now(),
            ]);
        }

        return $this;
    }

    /**
     * Remove amount from goal (withdrawal).
     */
    public function removeAmount($amount, $description = null)
    {
        $this->transactions()->create([
            'amount' => $amount,
            'type' => 'withdrawal',
            'description' => $description,
            'date' => now(),
        ]);

        $this->decrement('current_amount', $amount);

        // Ensure current amount doesn't go below 0
        if ($this->current_amount < 0) {
            $this->update(['current_amount' => 0]);
        }

        return $this;
    }

    /**
     * Process auto-save.
     */
    public function processAutoSave()
    {
        if (!$this->auto_save || !$this->auto_save_amount || $this->status !== 'active') {
            return false;
        }

        // Check if linked account has sufficient balance
        if ($this->linkedAccount && $this->linkedAccount->balance < $this->auto_save_amount) {
            return false;
        }

        $this->addContribution($this->auto_save_amount, 'Economia automática');

        // Deduct from linked account if exists
        if ($this->linkedAccount) {
            $this->linkedAccount->decrement('balance', $this->auto_save_amount);
        }

        return true;
    }

    /**
     * Complete the goal manually.
     */
    public function complete()
    {
        $this->update([
            'status' => 'completed',
            'achievement_date' => now(),
        ]);
    }

    /**
     * Pause the goal.
     */
    public function pause()
    {
        $this->update(['status' => 'paused']);
    }

    /**
     * Resume the goal.
     */
    public function resume()
    {
        $this->update(['status' => 'active']);
    }

    /**
     * Cancel the goal.
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
    }
}
