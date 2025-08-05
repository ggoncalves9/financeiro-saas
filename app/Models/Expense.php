<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Account;
use App\Models\Project;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Propriedades do modelo (para IDEs e análise estática).
 * @property float $amount
 * @property string $status
 * @property Carbon|null $due_date
 * @property bool $recurring
 * @property string|null $recurring_type
 * @property Carbon|null $recurring_until
 * @property int $user_id
 * @property int $account_id
 * @property int|null $project_id
 * @property bool $is_business_expense
 * @property bool $is_deductible
 * @property string|null $approval_status
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 */
class Expense extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get the category associated with the expense (compatível com o controller).
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

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
        'supplier_name',
        'supplier_document',
        'reference_number',
        'recurring',
        'recurring_type', // monthly, yearly, weekly
        'recurring_until',
        'status', // pending, paid, overdue
        'due_date',
        'tags',
        'notes',
        'receipt_url',
        'is_business_expense',
        'is_deductible',
        'cost_center',
        'department',
        'project_id',
        'approval_status', // pending, approved, rejected
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'recurring_until' => 'date',
        'approved_at' => 'datetime',
        'amount' => 'decimal:2',
        'recurring' => 'boolean',
        'is_business_expense' => 'boolean',
        'is_deductible' => 'boolean',
        'tags' => 'array',
    ];

    /**
     * Get the user that owns the expense.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the account that owns the expense.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the user who approved the expense.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the project associated with the expense.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the category associated with the expense.
     */
    public function categoryModel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the subcategory associated with the expense.
     */
    public function subcategoryModel()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    /**
     * Get formatted amount attribute.
     */
    public function getFormattedAmountAttribute()
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    /**
     * Scope for paid expenses.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for pending expenses.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for overdue expenses.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'pending')
                          ->where('due_date', '<', now()); // now() do Laravel
                    });
    }

    /**
     * Scope for current month.
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year); // now() do Laravel
    }

    /**
     * Scope for business expenses (PJ).
     */
    public function scopeBusiness($query)
    {
        return $query->where('is_business_expense', true);
    }

    /**
     * Scope for personal expenses (PF).
     */
    public function scopePersonal($query)
    {
        return $query->where('is_business_expense', false);
    }

    /**
     * Scope for deductible expenses.
     */
    public function scopeDeductible($query)
    {
        return $query->where('is_deductible', true);
    }

    /**
     * Scope for approved expenses.
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Check if expense is overdue.
     */
    public function isOverdue()
    {
        return $this->status === 'pending' && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Check if expense is due soon (within 3 days).
     */
    public function isDueSoon()
    {
        return $this->status === 'pending' && 
               $this->due_date && 
               $this->due_date->diffInDays(now()) <= 3 && 
               $this->due_date->isFuture();
    }

    /**
     * Generate next recurring expense.
     */
    public function generateNextRecurrence()
    {
        if (!$this->recurring || ($this->recurring_until && $this->recurring_until->isPast())) {
            return null;
        }

        $nextDate = $this->date;
        $nextDueDate = $this->due_date;
        
        switch ($this->recurring_type) {
            case 'weekly':
                $nextDate = $this->date->addWeek();
                $nextDueDate = $this->due_date ? $this->due_date->addWeek() : null;
                break;
            case 'monthly':
                $nextDate = $this->date->addMonth();
                $nextDueDate = $this->due_date ? $this->due_date->addMonth() : null;
                break;
            case 'yearly':
                $nextDate = $this->date->addYear();
                $nextDueDate = $this->due_date ? $this->due_date->addYear() : null;
                break;
        }

        return self::create([
            'user_id' => $this->user_id,
            'account_id' => $this->account_id,
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'date' => $nextDate,
            'due_date' => $nextDueDate,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'payment_method' => $this->payment_method,
            'supplier_name' => $this->supplier_name,
            'recurring' => $this->recurring,
            'recurring_type' => $this->recurring_type,
            'recurring_until' => $this->recurring_until,
            'status' => 'pending',
            'is_business_expense' => $this->is_business_expense,
            'is_deductible' => $this->is_deductible,
            'cost_center' => $this->cost_center,
            'department' => $this->department,
            'approval_status' => $this->is_business_expense ? 'pending' : 'approved',
        ]);
    }

    /**
     * Approve the expense.
     */
    public function approve($approvedBy = null)
    {
        $this->update([
            'approval_status' => 'approved',
            'approved_by' => $approvedBy ?? auth()->id(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Reject the expense.
     */
    public function reject($rejectedBy = null)
    {
        $this->update([
            'approval_status' => 'rejected',
            'approved_by' => $rejectedBy ?? auth()->id(),
            'approved_at' => now(),
        ]);
    }

    /**
     * Mark as paid.
     */
    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);
    }
}
