<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'billing_cycle',
        'trial_days',
        'max_users',
        'max_transactions',
        'features',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'trial_days' => 'integer',
        'max_users' => 'integer',
        'max_transactions' => 'integer',
        'sort_order' => 'integer'
    ];

    // Relacionamentos
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Métodos auxiliares
    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function getBillingCycleLabelAttribute()
    {
        return $this->billing_cycle === 'monthly' ? 'Mensal' : 'Anual';
    }

    public function hasFeature($feature)
    {
        return in_array($feature, $this->features ?? []);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }
}
