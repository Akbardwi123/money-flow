<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\InvestmentAllocation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class FinancialSurplusService
{
    /**
     * Calculate financial summary and surplus metrics for a specific user and period.
     *
     * @return array<string, mixed>
     */
    public function calculateForPeriod(User $user, ?int $month = null, ?int $year = null): array
    {
        $incomesQuery = $user->incomes()->forPeriod($month, $year);
        $expensesQuery = $user->expenses()->forPeriod($month, $year);
        $allocationsQuery = $user->investmentAllocations()->forPeriod($month, $year);

        $totalIncome = (float) $incomesQuery->sum('amount');

        $priorityExpenses = (float) (clone $expensesQuery)->priority()->sum('amount');
        $flexibleExpenses = (float) (clone $expensesQuery)->flexible()->sum('amount');
        $totalExpenses = $priorityExpenses + $flexibleExpenses;

        // Surplus Calculation: Total Income - Total Expenses
        $grossSurplus = $totalIncome - $totalExpenses;

        // Investment Allocations
        $financialInvestment = (float) (clone $allocationsQuery)->financial()->sum('amount');
        $skillInvestment = (float) (clone $allocationsQuery)->skill()->sum('amount');
        $totalAllocated = $financialInvestment + $skillInvestment;

        // Remaining Unallocated Surplus
        $remainingSurplus = $grossSurplus - $totalAllocated;

        // Ratios
        $priorityRatio = $totalIncome > 0 ? round(($priorityExpenses / $totalIncome) * 100, 1) : 0.0;
        $flexibleRatio = $totalIncome > 0 ? round(($flexibleExpenses / $totalIncome) * 100, 1) : 0.0;
        $surplusRate = $totalIncome > 0 ? round(($grossSurplus / $totalIncome) * 100, 1) : 0.0;

        $financialAllocationPercent = $grossSurplus > 0 ? round(($financialInvestment / $grossSurplus) * 100, 1) : 0.0;
        $skillAllocationPercent = $grossSurplus > 0 ? round(($skillInvestment / $grossSurplus) * 100, 1) : 0.0;
        $unallocatedPercent = $grossSurplus > 0 ? round((max(0, $remainingSurplus) / $grossSurplus) * 100, 1) : 0.0;

        // Status Determination
        $status = 'healthy';
        if ($grossSurplus < 0) {
            $status = 'deficit';
        } elseif ($grossSurplus === 0.0 && $totalIncome > 0) {
            $status = 'balanced';
        }

        // Expense categories breakdown
        $expenseCategories = (clone $expensesQuery)
            ->selectRaw('category, type, SUM(amount) as total')
            ->groupBy('category', 'type')
            ->orderByDesc('total')
            ->get();

        // Financial investment categories breakdown
        $financialCategories = (clone $allocationsQuery)
            ->financial()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // Skill investment categories breakdown
        $skillCategories = (clone $allocationsQuery)
            ->skill()
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return [
            'period' => [
                'month' => $month,
                'year' => $year,
                'label' => $month && $year ? Carbon::create($year, $month, 1)->translatedFormat('F Y') : ($year ? (string) $year : 'Semua Waktu'),
            ],
            'total_income' => $totalIncome,
            'priority_expenses' => $priorityExpenses,
            'flexible_expenses' => $flexibleExpenses,
            'total_expenses' => $totalExpenses,
            'gross_surplus' => $grossSurplus,
            'financial_investment' => $financialInvestment,
            'skill_investment' => $skillInvestment,
            'total_allocated' => $totalAllocated,
            'remaining_surplus' => $remainingSurplus,
            'priority_ratio' => $priorityRatio,
            'flexible_ratio' => $flexibleRatio,
            'surplus_rate' => $surplusRate,
            'financial_allocation_percent' => $financialAllocationPercent,
            'skill_allocation_percent' => $skillAllocationPercent,
            'unallocated_percent' => $unallocatedPercent,
            'status' => $status,
            'expense_categories' => $expenseCategories,
            'financial_categories' => $financialCategories,
            'skill_categories' => $skillCategories,
        ];
    }

    /**
     * Get 12-month trend for a specific year.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getMonthlyTrends(User $user, int $year): array
    {
        $trends = [];

        for ($m = 1; $m <= 12; $m++) {
            $incomes = (float) $user->incomes()->forPeriod($m, $year)->sum('amount');
            $priority = (float) $user->expenses()->priority()->forPeriod($m, $year)->sum('amount');
            $flexible = (float) $user->expenses()->flexible()->forPeriod($m, $year)->sum('amount');
            $expenses = $priority + $flexible;
            $surplus = $incomes - $expenses;
            $financial = (float) $user->investmentAllocations()->financial()->forPeriod($m, $year)->sum('amount');
            $skill = (float) $user->investmentAllocations()->skill()->forPeriod($m, $year)->sum('amount');

            $trends[] = [
                'month' => $m,
                'month_name' => Carbon::create($year, $m, 1)->translatedFormat('M'),
                'income' => $incomes,
                'priority_expense' => $priority,
                'flexible_expense' => $flexible,
                'expenses' => $expenses,
                'surplus' => $surplus,
                'financial_investment' => $financial,
                'skill_investment' => $skill,
            ];
        }

        return $trends;
    }

    /**
     * Get unified ledger containing incomes, priority/flexible expenses, and allocations.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function getUnifiedLedger(User $user, ?int $month = null, ?int $year = null, int $limit = 100): Collection
    {
        $incomes = $user->incomes()
            ->forPeriod($month, $year)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'transaction_type' => 'income',
                    'title' => $item->title,
                    'category' => $item->source,
                    'amount' => (float) $item->amount,
                    'direction' => 'in',
                    'date' => $item->date,
                    'notes' => $item->notes,
                    'badge' => 'Pemasukan',
                    'badge_color' => 'emerald',
                ];
            });

        $expenses = $user->expenses()
            ->forPeriod($month, $year)
            ->get()
            ->map(function ($item) {
                $isPriority = $item->type === Expense::TYPE_PRIORITY;

                return [
                    'id' => $item->id,
                    'transaction_type' => 'expense',
                    'sub_type' => $item->type,
                    'title' => $item->title,
                    'category' => $item->category,
                    'amount' => (float) $item->amount,
                    'direction' => 'out',
                    'date' => $item->date,
                    'notes' => $item->notes,
                    'badge' => $isPriority ? 'Prioritas (Wajib)' : 'Fleksibel (Harian)',
                    'badge_color' => $isPriority ? 'rose' : 'amber',
                ];
            });

        $allocations = $user->investmentAllocations()
            ->forPeriod($month, $year)
            ->get()
            ->map(function ($item) {
                $isFinancial = $item->type === InvestmentAllocation::TYPE_FINANCIAL;

                return [
                    'id' => $item->id,
                    'transaction_type' => 'allocation',
                    'sub_type' => $item->type,
                    'title' => $item->title,
                    'category' => $item->category.($item->platform ? " ({$item->platform})" : ''),
                    'amount' => (float) $item->amount,
                    'direction' => 'invest',
                    'date' => $item->date,
                    'notes' => $item->target_objective ?? $item->notes,
                    'badge' => $isFinancial ? 'Investasi Finansial' : 'Investasi Leher ke Atas',
                    'badge_color' => $isFinancial ? 'cyan' : 'violet',
                ];
            });

        return $incomes->concat($expenses)->concat($allocations)
            ->sortByDesc(fn ($item) => Carbon::parse($item['date'])->timestamp)
            ->take($limit)
            ->values();
    }
}
