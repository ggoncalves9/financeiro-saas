<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Subscription extends Model
{
    protected $fillable = [
        'user_id', 'status', 'plan', 'amount', 'monthly_revenue', 'currency', 'trial_ends_at', 'cancelled_at'
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'amount' => 'decimal:2',
        'monthly_revenue' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
