<?php

namespace App\Http\Controllers;

use App\Services\FinancialSurplusService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function __construct(
        protected FinancialSurplusService $surplusService
    ) {}

    /**
     * Display the full reports and portfolio dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $now = Carbon::now();

        $selectedMonth = $request->has('month') ? ($request->filled('month') ? (int) $request->input('month') : null) : $now->month;
        $selectedYear = $request->has('year') ? ($request->filled('year') ? (int) $request->input('year') : null) : $now->year;

        $metrics = $this->surplusService->calculateForPeriod($user, $selectedMonth, $selectedYear);
        $trends = $this->surplusService->getMonthlyTrends($user, $selectedYear ?? $now->year);
        $ledger = $this->surplusService->getUnifiedLedger($user, $selectedMonth, $selectedYear, 100);

        // Portfolio breakdown for all time
        $allTimeFinancial = $user->investmentAllocations()
            ->financial()
            ->selectRaw('category, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        $allTimeSkill = $user->investmentAllocations()
            ->skill()
            ->selectRaw('category, status, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('category', 'status')
            ->orderByDesc('total_amount')
            ->get();

        $skillMilestones = $user->investmentAllocations()
            ->skill()
            ->latest('date')
            ->get();

        return view('reports.index', [
            'metrics' => $metrics,
            'trends' => $trends,
            'ledger' => $ledger,
            'allTimeFinancial' => $allTimeFinancial,
            'allTimeSkill' => $allTimeSkill,
            'skillMilestones' => $skillMilestones,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ]);
    }

    /**
     * Export the unified ledger as CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $user = $request->user();
        $selectedMonth = $request->filled('month') ? (int) $request->input('month') : null;
        $selectedYear = $request->filled('year') ? (int) $request->input('year') : Carbon::now()->year;

        $ledger = $this->surplusService->getUnifiedLedger($user, $selectedMonth, $selectedYear, 1000);

        $fileName = 'moneyflow-laporan-'.($selectedYear ?? 'all').'-'.($selectedMonth ? str_pad((string) $selectedMonth, 2, '0', STR_PAD_LEFT) : 'all').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($ledger) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, ['Tanggal', 'Jenis Transaksi', 'Tipe / Kategori', 'Deskripsi / Judul', 'Arus Dana', 'Nominal (IDR)', 'Catatan / Target']);

            foreach ($ledger as $item) {
                fputcsv($handle, [
                    $item['date'],
                    $item['badge'],
                    $item['category'],
                    $item['title'],
                    $item['direction'] === 'in' ? 'Masuk (+)' : 'Keluar (-)',
                    number_format($item['amount'], 2, ',', '.'),
                    $item['notes'] ?? '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
