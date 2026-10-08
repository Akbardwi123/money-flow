<?php

namespace App\Models;

use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    /** @use HasFactory<ExpenseFactory> */
    use HasFactory;

    public const TYPE_PRIORITY = 'priority';

    public const TYPE_FLEXIBLE = 'flexible';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'category',
        'amount',
        'date',
        'notes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    /**
     * The user that owns the expense.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope query to priority expenses only.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopePriority(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_PRIORITY);
    }

    /**
     * Scope query to flexible expenses only.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeFlexible(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_FLEXIBLE);
    }

    /**
     * Scope query to a specific month and year.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeForPeriod(Builder $query, ?int $month = null, ?int $year = null): Builder
    {
        if ($year !== null) {
            $query->whereYear('date', $year);
        }

        if ($month !== null) {
            $query->whereMonth('date', $month);
        }

        return $query;
    }

    /**
     * Check if expense is priority.
     */
    public function isPriority(): bool
    {
        return $this->type === self::TYPE_PRIORITY;
    }

    /**
     * Check if expense is flexible.
     */
    public function isFlexible(): bool
    {
        return $this->type === self::TYPE_FLEXIBLE;
    }
}
