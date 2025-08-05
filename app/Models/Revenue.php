<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revenue extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'account_id',
        'title',
        'description',
        'amount',
        'date',
        'category',
        'subcategory',
        'payment_method',
        'reference_number',
        'client_name',
        'client_document',
        'recurring',
        'recurring_type', // monthly, yearly, weekly
        'recurring_until',
        'status', // pending, confirmed, cancelled
        'tags',
        'notes',
        'receipt_url',
        'is_business_revenue',
        'tax_rate',
        'tax_amount',
        'net_amount',
        'invoice_number',
        'contract_id',
    ];

    protected $casts = [
        'date' => 'date',
        'recurring_until' => 'date',
        'amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'recurring' => 'boolean',
        'is_business_revenue' => 'boolean',
        'tags' => 'array',
    ];

    /**
     * Get the user that owns the revenue.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account that owns the revenue.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the contract associated with the revenue (for PJ).
     */
    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Scope for confirmed revenues.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for pending revenues.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for current month.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    /**
     * Scope for business revenues (PJ).
     */
    public function scopeBusiness($query)
    {
        return $query->where('is_business_revenue', true);
    }

    /**
     * Scope for personal revenues (PF).
     */
    public function scopePersonal($query)
    {
        return $query->where('is_business_revenue', false);
    }

    /**
     * Calculate net amount after taxes.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($revenue) {
            if ($revenue->tax_rate > 0) {
                $revenue->tax_amount = $revenue->amount * ($revenue->tax_rate / 100);
                $revenue->net_amount = $revenue->amount - $revenue->tax_amount;
            } else {
                $revenue->tax_amount = 0;
                $revenue->net_amount = $revenue->amount;
            }
        });
    }

    /**
     * Get formatted amount.
     */
    public function getFormattedAmountAttribute()
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    /**
     * Get formatted net amount.
     */
    public function getFormattedNetAmountAttribute()
    {
        return 'R$ ' . number_format($this->net_amount, 2, ',', '.');
    }

    /**
     * Check if revenue is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->date->isPast();
    }

    /**
     * Generate next recurring revenue.
     */
    public function generateNextRecurrence()
    {
        if (!$this->recurring || ($this->recurring_until && $this->recurring_until->isPast())) {
            return null;
        }

        $nextDate = $this->date;
        
        switch ($this->recurring_type) {
            case 'weekly':
                $nextDate = $this->date->addWeek();
                break;
            case 'monthly':
                $nextDate = $this->date->addMonth();
                break;
            case 'yearly':
                $nextDate = $this->date->addYear();
                break;
        }

        return self::create([
            'user_id' => $this->user_id,
            'account_id' => $this->account_id,
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $nextDate,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'payment_method' => $this->payment_method,
            'recurring' => $this->recurring,
            'recurring_type' => $this->recurring_type,
            'recurring_until' => $this->recurring_until,
            'status' => 'pending',
            'is_business_revenue' => $this->is_business_revenue,
            'tax_rate' => $this->tax_rate,
        ]);
    }
}
