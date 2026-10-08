<?php

namespace App\Models;

use Database\Factories\IncomeFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Income extends Model
{
    /** @use HasFactory<IncomeFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'source',
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
     * The user that owns the income.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
}
