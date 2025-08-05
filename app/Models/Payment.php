<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'transaction_id',
        'txid',
        'amount',
        'currency',
        'status',
        'payment_method',
        'gateway_response',
        'qr_code',
        'qr_code_image',
        'expires_at',
        'paid_at',
        'description',
        'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'metadata' => 'array',
        'expires_at' => 'datetime',
        'paid_at' => 'datetime'
    ];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Pendente',
            'paid' => 'Pago',
            'failed' => 'Falhou',
            'cancelled' => 'Cancelado',
            'refunded' => 'Reembolsado',
            default => 'Desconhecido'
        };
    }

    public function getPaymentMethodLabelAttribute()
    {
        return match($this->payment_method) {
            'pix' => 'PIX',
            'credit_card' => 'Cartão de Crédito',
            'bank_slip' => 'Boleto Bancário',
            default => 'Desconhecido'
        };
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }
}
