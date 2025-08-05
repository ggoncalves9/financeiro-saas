<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type', // checking, savings, credit_card, investment, cash
        'bank_name',
        'account_number',
        'agency',
        'balance',
        'credit_limit',
        'interest_rate',
        'currency',
        'is_active',
        'description',
        'color',
        'icon',
        'sync_enabled',
        'sync_last_date',
        'bank_integration_id',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'credit_limit' => 'decimal:2',
        'interest_rate' => 'decimal:4',
        'is_active' => 'boolean',
        'sync_enabled' => 'boolean',
        'sync_last_date' => 'datetime',
    ];

    /**
     * Get the user that owns the account.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the revenues for this account.
     */
    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    /**
     * Get the expenses for this account.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the goals linked to this account.
     */
    public function linkedGoals()
    {
        return $this->hasMany(Goal::class, 'linked_account_id');
    }

    /**
     * Get the transactions for this account.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Scope for active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for credit card accounts.
     */
    public function scopeCreditCards($query)
    {
        return $query->where('type', 'credit_card');
    }

    /**
     * Scope for bank accounts.
     */
    public function scopeBankAccounts($query)
    {
        return $query->whereIn('type', ['checking', 'savings']);
    }

    /**
     * Get formatted balance.
     */
    public function getFormattedBalanceAttribute()
    {
        return 'R$ ' . number_format($this->balance, 2, ',', '.');
    }

    /**
     * Get formatted credit limit.
     */
    public function getFormattedCreditLimitAttribute()
    {
        return 'R$ ' . number_format($this->credit_limit ?? 0, 2, ',', '.');
    }

    /**
     * Get available credit (for credit cards).
     */
    public function getAvailableCreditAttribute()
    {
        if ($this->type !== 'credit_card' || !$this->credit_limit) {
            return 0;
        }

        return $this->credit_limit + $this->balance; // balance is negative for credit cards
    }

    /**
     * Get formatted available credit.
     */
    public function getFormattedAvailableCreditAttribute()
    {
        return 'R$ ' . number_format($this->available_credit, 2, ',', '.');
    }

    /**
     * Check if account is credit card.
     */
    public function isCreditCard()
    {
        return $this->type === 'credit_card';
    }

    /**
     * Check if account is bank account.
     */
    public function isBankAccount()
    {
        return in_array($this->type, ['checking', 'savings']);
    }

    /**
     * Add transaction to account.
     */
    public function addTransaction($amount, $type, $description = null, $category = null)
    {
        $transaction = $this->transactions()->create([
            'user_id' => $this->user_id,
            'amount' => $amount,
            'type' => $type, // credit, debit
            'description' => $description,
            'category' => $category,
            'date' => now(),
        ]);

        // Update balance
        if ($type === 'credit') {
            $this->increment('balance', $amount);
        } else {
            $this->decrement('balance', $amount);
        }

        return $transaction;
    }

    /**
     * Transfer money to another account.
     */
    public function transferTo(Account $toAccount, $amount, $description = null)
    {
        if ($this->balance < $amount && !$this->isCreditCard()) {
            throw new \Exception('Saldo insuficiente para transferência.');
        }

        // Create debit transaction in source account
        $this->addTransaction($amount, 'debit', "Transferência para {$toAccount->name}: {$description}");

        // Create credit transaction in destination account
        $toAccount->addTransaction($amount, 'credit', "Transferência de {$this->name}: {$description}");

        return true;
    }

    /**
     * Calculate total income for period.
     */
    public function getTotalIncome($startDate = null, $endDate = null)
    {
        $query = $this->revenues();

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    /**
     * Calculate total expenses for period.
     */
    public function getTotalExpenses($startDate = null, $endDate = null)
    {
        $query = $this->expenses();

        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }

        return $query->sum('amount');
    }

    /**
     * Get monthly balance summary.
     */
    public function getMonthlyBalance($month = null, $year = null)
    {
        $month = $month ?? now()->month;
        $year = $year ?? now()->year;

        $income = $this->getTotalIncome(
            now()->setYear($year)->setMonth($month)->startOfMonth(),
            now()->setYear($year)->setMonth($month)->endOfMonth()
        );

        $expenses = $this->getTotalExpenses(
            now()->setYear($year)->setMonth($month)->startOfMonth(),
            now()->setYear($year)->setMonth($month)->endOfMonth()
        );

        return [
            'income' => $income,
            'expenses' => $expenses,
            'balance' => $income - $expenses,
            'formatted_income' => 'R$ ' . number_format($income, 2, ',', '.'),
            'formatted_expenses' => 'R$ ' . number_format($expenses, 2, ',', '.'),
            'formatted_balance' => 'R$ ' . number_format($income - $expenses, 2, ',', '.'),
        ];
    }

    /**
     * Sync account with bank (placeholder for future implementation).
     */
    public function syncWithBank()
    {
        if (!$this->sync_enabled || !$this->bank_integration_id) {
            return false;
        }

        // TODO: Implement bank API integration
        $this->update(['sync_last_date' => now()]);

        return true;
    }
}
