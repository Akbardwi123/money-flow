<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvestmentAllocationRequest;
use App\Http\Requests\UpdateInvestmentAllocationRequest;
use App\Models\InvestmentAllocation;
use App\Services\FinancialSurplusService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InvestmentAllocationController extends Controller
{
    public function __construct(
        protected FinancialSurplusService $surplusService
    ) {}

    /**
     * Display the investment allocation hub.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $now = Carbon::now();

        $selectedMonth = $request->has('month') ? ($request->filled('month') ? (int) $request->input('month') : null) : $now->month;
        $selectedYear = $request->has('year') ? ($request->filled('year') ? (int) $request->input('year') : null) : $now->year;
        $typeFilter = $request->input('type'); // 'financial' or 'skill' or null
        $statusFilter = $request->input('status');
        $search = $request->input('search');

        // Calculate surplus and allocation metrics
        $metrics = $this->surplusService->calculateForPeriod($user, $selectedMonth, $selectedYear);

        $query = $user->investmentAllocations()
            ->forPeriod($selectedMonth, $selectedYear)
            ->latest('date');

        if ($typeFilter) {
            $query->where('type', $typeFilter);
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('platform', 'like', "%{$search}%")
                    ->orWhere('target_objective', 'like', "%{$search}%");
            });
        }

        $allocations = $query->paginate(15)->withQueryString();

        // Get distinct platforms and categories
        $platforms = $user->investmentAllocations()->whereNotNull('platform')->select('platform')->distinct()->pluck('platform');

        // Split lists for the Hub dual view
        $financialList = $user->investmentAllocations()
            ->financial()
            ->forPeriod($selectedMonth, $selectedYear)
            ->latest('date')
            ->get();

        $skillList = $user->investmentAllocations()
            ->skill()
            ->forPeriod($selectedMonth, $selectedYear)
            ->latest('date')
            ->get();

        return view('allocations.index', [
            'metrics' => $metrics,
            'allocations' => $allocations,
            'financialList' => $financialList,
            'skillList' => $skillList,
            'platforms' => $platforms,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'typeFilter' => $typeFilter,
            'statusFilter' => $statusFilter,
            'search' => $search,
        ]);
    }

    /**
     * Store a newly created allocation in storage.
     */
    public function store(StoreInvestmentAllocationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if (empty($validated['status'])) {
            $validated['status'] = $validated['type'] === InvestmentAllocation::TYPE_SKILL ? 'planned' : 'active';
        }

        $request->user()->investmentAllocations()->create($validated);

        $label = $validated['type'] === InvestmentAllocation::TYPE_FINANCIAL
            ? 'Investasi Finansial'
            : 'Investasi Leher ke Atas (Skill)';

        return redirect()->route('allocations.index', [
            'month' => Carbon::parse($validated['date'])->month,
            'year' => Carbon::parse($validated['date'])->year,
            'type' => $validated['type'],
        ])->with('success', "Alokasi {$label} berhasil disimpan ke portofolio.");
    }

    /**
     * Show the form for editing the specified allocation.
     */
    public function edit(InvestmentAllocation $allocation): View
    {
        $this->authorizeOwner($allocation);

        return view('allocations.edit', [
            'allocation' => $allocation,
        ]);
    }

    /**
     * Update the specified allocation in storage.
     */
    public function update(UpdateInvestmentAllocationRequest $request, InvestmentAllocation $allocation): RedirectResponse
    {
        $this->authorizeOwner($allocation);

        $allocation->update($request->validated());

        return redirect()->route('allocations.index', [
            'month' => Carbon::parse($allocation->date)->month,
            'year' => Carbon::parse($allocation->date)->year,
        ])->with('success', 'Data alokasi investasi berhasil diperbarui.');
    }

    /**
     * Remove the specified allocation from storage.
     */
    public function destroy(InvestmentAllocation $allocation): RedirectResponse
    {
        $this->authorizeOwner($allocation);

        $month = Carbon::parse($allocation->date)->month;
        $year = Carbon::parse($allocation->date)->year;

        $allocation->delete();

        return redirect()->route('allocations.index', [
            'month' => $month,
            'year' => $year,
        ])->with('success', 'Alokasi investasi berhasil dihapus.');
    }

    /**
     * Authorize that the authenticated user owns the resource.
     */
    protected function authorizeOwner(InvestmentAllocation $allocation): void
    {
        if ($allocation->user_id !== Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }
}
