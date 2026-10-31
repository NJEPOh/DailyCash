<?php

namespace App\Models;

use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /** @use HasFactory<TransactionFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'account_id',
        'category_id',
        'type',
        'amount',
        'date',
        'description',
        'proof_image',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'date' => 'date',
        ];
    }

    /**
     * Get the account that owns the transaction.
     *
     * @return BelongsTo<Account, $this>
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the category that owns the transaction.
     *
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to apply transaction filters.
     *
     * @param  Builder<Transaction>  $query
     * @param  array{start_date?: ?string, end_date?: ?string, category_id?: int|string|null, type?: ?string, account_id?: int|string|null}  $filters
     * @return Builder<Transaction>
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['start_date'] ?? null, fn (Builder $q, string $startDate) => $q->whereDate('date', '>=', $startDate))
            ->when($filters['end_date'] ?? null, fn (Builder $q, string $endDate) => $q->whereDate('date', '<=', $endDate))
            ->when($filters['category_id'] ?? null, fn (Builder $q, int|string $categoryId) => $q->where('category_id', $categoryId))
            ->when($filters['type'] ?? null, fn (Builder $q, string $type) => $q->where('type', $type))
            ->when($filters['account_id'] ?? null, fn (Builder $q, int|string $accountId) => $q->where('account_id', $accountId));
    }

    /**
     * Get formatted Rupiah amount string.
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp'.number_format((float) $this->amount, 0, ',', '.');
    }

    /**
     * Get the Indonesian label for the transaction type.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'income' ? 'Pemasukan' : 'Pengeluaran';
    }
}
