<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses (Priority & Flexible).
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $now = Carbon::now();

        $selectedMonth = $request->has('month') ? ($request->filled('month') ? (int) $request->input('month') : null) : $now->month;
        $selectedYear = $request->has('year') ? ($request->filled('year') ? (int) $request->input('year') : null) : $now->year;
        $typeFilter = $request->input('type'); // 'priority' or 'flexible' or null (all)
        $categoryFilter = $request->input('category');
        $search = $request->input('search');

        $query = $user->expenses()
            ->forPeriod($selectedMonth, $selectedYear)
            ->latest('date');

        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }

        if ($categoryFilter) {
            $query->where('category', $categoryFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $expenses = $query->paginate(15)->withQueryString();

        // Calculate totals for period
        $basePeriodQuery = $user->expenses()->forPeriod($selectedMonth, $selectedYear);
        $totalPriority = (float) (clone $basePeriodQuery)->priority()->sum('amount');
        $totalFlexible = (float) (clone $basePeriodQuery)->flexible()->sum('amount');
        $totalExpenses = $totalPriority + $totalFlexible;

        // Categories list for filtering
        $categories = $user->expenses()->select('category')->distinct()->pluck('category');

        return view('expenses.index', [
            'expenses' => $expenses,
            'totalPriority' => $totalPriority,
            'totalFlexible' => $totalFlexible,
            'totalExpenses' => $totalExpenses,
            'categories' => $categories,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'typeFilter' => $typeFilter,
            'categoryFilter' => $categoryFilter,
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(StoreExpenseRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $request->user()->expenses()->create($validated);

        return redirect()->route('expenses.index', [
            'month' => Carbon::parse($validated['date'])->month,
            'year' => Carbon::parse($validated['date'])->year,
            'type' => $validated['type'],
        ])->with('success', 'Pengeluaran ('.($validated['type'] === Expense::TYPE_PRIORITY ? 'Prioritas / Wajib' : 'Fleksibel / Harian').') berhasil dicatat.');
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense): View
    {
        $this->authorizeOwner($expense);

        return view('expenses.edit', [
            'expense' => $expense,
        ]);
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(UpdateExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $this->authorizeOwner($expense);

        $expense->update($request->validated());

        return redirect()->route('expenses.index', [
            'month' => Carbon::parse($expense->date)->month,
            'year' => Carbon::parse($expense->date)->year,
        ])->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        $this->authorizeOwner($expense);

        $month = Carbon::parse($expense->date)->month;
        $year = Carbon::parse($expense->date)->year;

        $expense->delete();

        return redirect()->route('expenses.index', [
            'month' => $month,
            'year' => $year,
        ])->with('success', 'Pengeluaran berhasil dihapus.');
    }

    /**
     * Authorize that the authenticated user owns the resource.
     */
    protected function authorizeOwner(Expense $expense): void
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }
}
