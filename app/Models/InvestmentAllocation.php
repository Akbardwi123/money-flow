<?php

namespace App\Models;

use Database\Factories\InvestmentAllocationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentAllocation extends Model
{
    /** @use HasFactory<InvestmentAllocationFactory> */
    use HasFactory;

    public const TYPE_FINANCIAL = 'financial';

    public const TYPE_SKILL = 'skill';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'category',
        'platform',
        'amount',
        'date',
        'target_objective',
        'status',
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
     * The user that owns the investment allocation.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope query to financial investments only.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeFinancial(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_FINANCIAL);
    }

    /**
     * Scope query to self development / skill investments only.
     *
     * @param  Builder<$this>  $query
     * @return Builder<$this>
     */
    public function scopeSkill(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_SKILL);
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
     * Check if allocation is financial.
     */
    public function isFinancial(): bool
    {
        return $this->type === self::TYPE_FINANCIAL;
    }

    /**
     * Check if allocation is skill / self-development.
     */
    public function isSkill(): bool
    {
        return $this->type === self::TYPE_SKILL;
    }
}
