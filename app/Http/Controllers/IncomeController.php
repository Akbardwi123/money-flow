<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreIncomeRequest;
use App\Http\Requests\UpdateIncomeRequest;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class IncomeController extends Controller
{
    /**
     * Display a listing of incomes.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $now = Carbon::now();

        $selectedMonth = $request->has('month') ? ($request->filled('month') ? (int) $request->input('month') : null) : $now->month;
        $selectedYear = $request->has('year') ? ($request->filled('year') ? (int) $request->input('year') : null) : $now->year;
        $sourceFilter = $request->input('source');
        $search = $request->input('search');

        $query = $user->incomes()
            ->forPeriod($selectedMonth, $selectedYear)
            ->latest('date');

        if ($sourceFilter) {
            $query->where('source', $sourceFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $incomes = $query->paginate(15)->withQueryString();
        $totalIncome = (float) $user->incomes()->forPeriod($selectedMonth, $selectedYear)->sum('amount');
        $sources = $user->incomes()->select('source')->distinct()->pluck('source');

        return view('incomes.index', [
            'incomes' => $incomes,
            'totalIncome' => $totalIncome,
            'sources' => $sources,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'sourceFilter' => $sourceFilter,
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created income in storage.
     */
    public function store(StoreIncomeRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $request->user()->incomes()->create($validated);

        return redirect()->route('incomes.index', [
            'month' => Carbon::parse($validated['date'])->month,
            'year' => Carbon::parse($validated['date'])->year,
        ])->with('success', 'Pemasukan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified income.
     */
    public function edit(Income $income): View
    {
        $this->authorizeOwner($income);

        return view('incomes.edit', [
            'income' => $income,
        ]);
    }

    /**
     * Update the specified income in storage.
     */
    public function update(UpdateIncomeRequest $request, Income $income): RedirectResponse
    {
        $this->authorizeOwner($income);

        $income->update($request->validated());

        return redirect()->route('incomes.index', [
            'month' => Carbon::parse($income->date)->month,
            'year' => Carbon::parse($income->date)->year,
        ])->with('success', 'Pemasukan berhasil diperbarui.');
    }

    /**
     * Remove the specified income from storage.
     */
    public function destroy(Income $income): RedirectResponse
    {
        $this->authorizeOwner($income);

        $month = Carbon::parse($income->date)->month;
        $year = Carbon::parse($income->date)->year;

        $income->delete();

        return redirect()->route('incomes.index', [
            'month' => $month,
            'year' => $year,
        ])->with('success', 'Pemasukan berhasil dihapus.');
    }

    /**
     * Authorize that the authenticated user owns the resource.
     */
    protected function authorizeOwner(Income $income): void
    {
        if ($income->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }
}
