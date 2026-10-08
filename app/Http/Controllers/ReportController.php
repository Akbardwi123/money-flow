<?php

namespace App\Http\Controllers;

use App\Services\FinancialSurplusService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
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
            // Add UTF-8 BOM so Excel opens UTF-8 Indonesian text correctly
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Semicolon (;) is the standard Excel list separator in Indonesia
            $delimiter = ';';

            // CSV Header
            fputcsv($handle, [
                'Tanggal',
                'Jenis Transaksi',
                'Tipe / Kategori',
                'Deskripsi / Judul',
                'Arus Dana',
                'Nominal (IDR)',
                'Catatan / Target',
            ], $delimiter);

            foreach ($ledger as $item) {
                // Format clean date without timestamp
                $cleanDate = Carbon::parse($item['date'])->format('Y-m-d');

                // Sanitize notes from line breaks and tabs so it stays on a single Excel row
                $cleanNotes = str_replace(["\r\n", "\r", "\n", "\t"], ' ', (string) ($item['notes'] ?? '-'));
                $cleanNotes = preg_replace('/\s+/', ' ', trim($cleanNotes));

                fputcsv($handle, [
                    $cleanDate,
                    $item['badge'],
                    $item['category'],
                    $item['title'],
                    $item['direction'] === 'in' ? 'Masuk (+)' : 'Keluar (-)',
                    (int) round($item['amount']),
                    $cleanNotes ?: '-',
                ], $delimiter);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export the unified ledger as a professionally styled XLSX Excel file.
     */
    public function exportExcel(Request $request): StreamedResponse
    {
        $user = $request->user();
        $selectedMonth = $request->filled('month') ? (int) $request->input('month') : null;
        $selectedYear = $request->filled('year') ? (int) $request->input('year') : Carbon::now()->year;

        $ledger = $this->surplusService->getUnifiedLedger($user, $selectedMonth, $selectedYear, 2000);

        $monthName = $selectedMonth ? Carbon::create(2026, $selectedMonth, 1)->translatedFormat('F') : 'Semua Bulan';
        $periodLabel = "{$monthName} {$selectedYear}";
        $fileName = 'moneyflow-laporan-'.($selectedYear ?? 'all').'-'.($selectedMonth ? str_pad((string) $selectedMonth, 2, '0', STR_PAD_LEFT) : 'all').'.xlsx';

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Keuangan');

        // Document Metadata
        $spreadsheet->getProperties()
            ->setCreator('MoneyFlow')
            ->setTitle("Laporan Keuangan - {$periodLabel}")
            ->setSubject('Laporan Mutasi Kas & Alokasi Surplus');

        // 1. Title Banner (Rows 1-2)
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'MONEYFLOW — LAPORAN MUTASI KEUANGAN & ALOKASI SURPLUS');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new Color('FF0F172A'));
        $sheet->getRowDimension(1)->setRowHeight(28);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', "Periode: {$periodLabel} | Pengguna: {$user->name} ({$user->email}) | Dicetak: ".Carbon::now()->translatedFormat('d F Y H:i'));
        $sheet->getStyle('A2')->getFont()->setSize(9)->setColor(new Color('FF64748B'));
        $sheet->getRowDimension(2)->setRowHeight(18);

        // 2. Table Column Headers (Row 4)
        $headerRow = 4;
        $headers = [
            'A' => 'No',
            'B' => 'Tanggal',
            'C' => 'Jenis Transaksi',
            'D' => 'Kategori',
            'E' => 'Deskripsi / Judul',
            'F' => 'Arus Dana',
            'G' => 'Nominal (IDR)',
            'H' => 'Catatan / Target',
        ];

        foreach ($headers as $col => $text) {
            $sheet->setCellValue("{$col}{$headerRow}", $text);
        }

        $sheet->getRowDimension($headerRow)->setRowHeight(26);
        $headerRange = "A{$headerRow}:H{$headerRow}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E293B'], // Slate 800
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle("D{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("E{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getStyle("G{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("H{$headerRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // 3. Populate Data Rows
        $currentRow = 5;
        $no = 1;

        foreach ($ledger as $item) {
            $cleanDate = Carbon::parse($item['date'])->format('Y-m-d');
            $cleanNotes = str_replace(["\r\n", "\r", "\n", "\t"], ' ', (string) ($item['notes'] ?? '-'));
            $cleanNotes = preg_replace('/\s+/', ' ', trim($cleanNotes));
            $isIncome = $item['direction'] === 'in';
            $directionText = $isIncome ? 'Masuk (+)' : 'Keluar (-)';
            $amount = (float) $item['amount'];

            $sheet->setCellValue("A{$currentRow}", $no);
            $sheet->setCellValue("B{$currentRow}", $cleanDate);
            $sheet->setCellValue("C{$currentRow}", $item['badge']);
            $sheet->setCellValue("D{$currentRow}", $item['category']);
            $sheet->setCellValue("E{$currentRow}", $item['title']);
            $sheet->setCellValue("F{$currentRow}", $directionText);
            $sheet->setCellValue("G{$currentRow}", $amount);
            $sheet->setCellValue("H{$currentRow}", $cleanNotes ?: '-');

            // Format number cell as Currency
            $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');

            // Alignments
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("B{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("C{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("F{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

            // Row zebra striping
            if ($currentRow % 2 === 0) {
                $sheet->getStyle("A{$currentRow}:H{$currentRow}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF8FAFC'); // subtle slate 50
            }

            // Direction text coloring
            if ($isIncome) {
                $sheet->getStyle("F{$currentRow}")->getFont()->setColor(new Color('FF059669'))->setBold(true);
            } else {
                $sheet->getStyle("F{$currentRow}")->getFont()->setColor(new Color('FFE11D48'));
            }

            $sheet->getRowDimension($currentRow)->setRowHeight(20);
            $currentRow++;
            $no++;
        }

        $lastDataRow = $currentRow - 1;

        // 4. Total Summary Row
        if ($lastDataRow >= 5) {
            $sheet->mergeCells("A{$currentRow}:F{$currentRow}");
            $sheet->setCellValue("A{$currentRow}", 'TOTAL NOMINAL MUTASI');
            $sheet->setCellValue("G{$currentRow}", "=SUM(G5:G{$lastDataRow})");
            $sheet->setCellValue("H{$currentRow}", "{$ledger->count()} Transaksi");

            $sheet->getStyle("G{$currentRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->getRowDimension($currentRow)->setRowHeight(24);

            $summaryRange = "A{$currentRow}:H{$currentRow}";
            $sheet->getStyle($summaryRange)->applyFromArray([
                'font' => ['bold' => true, 'size' => 10],
                'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFF1F5F9'], // Slate 100
                ],
            ]);
            $sheet->getStyle("A{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("G{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle("H{$currentRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Double bottom border for accounting total
            $sheet->getStyle("G{$currentRow}")->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);

            $tableEndRow = $currentRow;
        } else {
            $tableEndRow = 4;
        }

        // Apply Borders to entire table grid
        $fullTableRange = "A4:H{$tableEndRow}";
        $sheet->getStyle($fullTableRange)->getBorders()->getAllBorders()->applyFromArray([
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FFE2E8F0'], // Slate 200
        ]);

        // Auto-fit all column widths
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Freeze pane below headers
        $sheet->freezePane('A5');

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
