<?php

namespace App\Http\Controllers;

use App\Services\FinancialSurplusService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected FinancialSurplusService $surplusService
    ) {}

    /**
     * Display the main dashboard with real-time surplus calculations and overview.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Default to current month and year if not specified, or allow null for all-time
        $now = Carbon::now();
        $selectedMonth = $request->has('month') ? ($request->filled('month') ? (int) $request->input('month') : null) : $now->month;
        $selectedYear = $request->has('year') ? ($request->filled('year') ? (int) $request->input('year') : null) : $now->year;

        $metrics = $this->surplusService->calculateForPeriod($user, $selectedMonth, $selectedYear);
        $trends = $this->surplusService->getMonthlyTrends($user, $selectedYear ?? $now->year);

        $recentIncomes = $user->incomes()
            ->forPeriod($selectedMonth, $selectedYear)
            ->latest('date')
            ->take(5)
            ->get();

        $recentExpenses = $user->expenses()
            ->forPeriod($selectedMonth, $selectedYear)
            ->latest('date')
            ->take(6)
            ->get();

        $financialAllocations = $user->investmentAllocations()
            ->financial()
            ->forPeriod($selectedMonth, $selectedYear)
            ->latest('date')
            ->take(5)
            ->get();

        $skillAllocations = $user->investmentAllocations()
            ->skill()
            ->forPeriod($selectedMonth, $selectedYear)
            ->latest('date')
            ->take(5)
            ->get();

        return view('dashboard', [
            'metrics' => $metrics,
            'trends' => $trends,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'recentIncomes' => $recentIncomes,
            'recentExpenses' => $recentExpenses,
            'financialAllocations' => $financialAllocations,
            'skillAllocations' => $skillAllocations,
        ]);
    }
}
