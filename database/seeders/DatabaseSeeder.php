<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Income;
use App\Models\InvestmentAllocation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create demo user
        $user = User::firstOrCreate(
            ['email' => 'user@moneyflow.test'],
            [
                'name' => 'Aditya Pratama',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        // Clean existing records for fresh demo run
        $user->incomes()->delete();
        $user->expenses()->delete();
        $user->investmentAllocations()->delete();

        // Months to seed: 3 months (2 months ago, last month, current month)
        $monthsToSeed = [
            Carbon::create($currentYear, $currentMonth, 1)->subMonths(2),
            Carbon::create($currentYear, $currentMonth, 1)->subMonth(),
            Carbon::create($currentYear, $currentMonth, 1),
        ];

        foreach ($monthsToSeed as $dateObj) {
            $year = $dateObj->year;
            $month = $dateObj->month;

            // 1. Incomes
            Income::create([
                'user_id' => $user->id,
                'title' => 'Gaji Pokok Software Engineer',
                'source' => 'Gaji Pokok',
                'amount' => 15000000,
                'date' => Carbon::create($year, $month, 25)->toDateString(),
                'notes' => 'Gaji bulanan reguler dari kantor utama',
            ]);

            Income::create([
                'user_id' => $user->id,
                'title' => 'Project Freelance Security Audit',
                'source' => 'Freelance',
                'amount' => 6500000,
                'date' => Carbon::create($year, $month, 10)->toDateString(),
                'notes' => 'Audit keamanan web fintech dan vulnerability assessment',
            ]);

            Income::create([
                'user_id' => $user->id,
                'title' => 'Dividen Portofolio Saham & SBN',
                'source' => 'Dividen / Passive Income',
                'amount' => 1250000,
                'date' => Carbon::create($year, $month, 15)->toDateString(),
                'notes' => 'Distribusi kupon ORI & dividen interim',
            ]);

            // 2. Expenses - Priority (Wajib / Tagihan)
            Expense::create([
                'user_id' => $user->id,
                'type' => Expense::TYPE_PRIORITY,
                'title' => 'Sewa Apartemen & Maintenance Fee',
                'category' => 'Tempat Tinggal',
                'amount' => 3800000,
                'date' => Carbon::create($year, $month, 2)->toDateString(),
                'notes' => 'Sewa hunian bulanan + sinking fund',
            ]);

            Expense::create([
                'user_id' => $user->id,
                'type' => Expense::TYPE_PRIORITY,
                'title' => 'Tagihan Listrik PLN & Air PDAM',
                'category' => 'Utilitas',
                'amount' => 750000,
                'date' => Carbon::create($year, $month, 5)->toDateString(),
                'notes' => 'Token listrik 2200VA + air bersih',
            ]);

            Expense::create([
                'user_id' => $user->id,
                'type' => Expense::TYPE_PRIORITY,
                'title' => 'Internet Fiber Optic 100 Mbps',
                'category' => 'Internet & Komunikasi',
                'amount' => 450000,
                'date' => Carbon::create($year, $month, 6)->toDateString(),
                'notes' => 'Koneksi kerja remote dan lab cybersecurity',
            ]);

            Expense::create([
                'user_id' => $user->id,
                'type' => Expense::TYPE_PRIORITY,
                'title' => 'Belanja Sembako & Bahan Makanan Pokok',
                'category' => 'Makanan Pokok',
                'amount' => 2800000,
                'date' => Carbon::create($year, $month, 7)->toDateString(),
                'notes' => 'Bahan pangan mingguan di supermarket',
            ]);

            Expense::create([
                'user_id' => $user->id,
                'type' => Expense::TYPE_PRIORITY,
                'title' => 'Premi Asuransi Kesehatan & BPJS',
                'category' => 'Asuransi & Proteksi',
                'amount' => 950000,
                'date' => Carbon::create($year, $month, 8)->toDateString(),
                'notes' => 'Asuransi rawat inap murni + BPJS Kelas 1',
            ]);

            // 3. Expenses - Flexible (Harian / Opsional)
            Expense::create([
                'user_id' => $user->id,
                'type' => Expense::TYPE_FLEXIBLE,
                'title' => 'Kopi Artisan & Working Space Cafe',
                'category' => 'Kuliner & Nongkrong',
                'amount' => 650000,
                'date' => Carbon::create($year, $month, 12)->toDateString(),
                'notes' => 'Sesi ngopi sambil riset dan bug bounty',
            ]);

            Expense::create([
                'user_id' => $user->id,
                'type' => Expense::TYPE_FLEXIBLE,
                'title' => 'Langganan Streaming & Cloud Storage',
                'category' => 'Hiburan & Langganan',
                'amount' => 320000,
                'date' => Carbon::create($year, $month, 14)->toDateString(),
                'notes' => 'Spotify Family, Netflix UHD, Google One 2TB',
            ]);

            Expense::create([
                'user_id' => $user->id,
                'type' => Expense::TYPE_FLEXIBLE,
                'title' => 'Dinner Weekend Bersama Rekan',
                'category' => 'Kuliner & Nongkrong',
                'amount' => 850000,
                'date' => Carbon::create($year, $month, 18)->toDateString(),
                'notes' => 'Makan malam networking santai',
            ]);

            Expense::create([
                'user_id' => $user->id,
                'type' => Expense::TYPE_FLEXIBLE,
                'title' => 'Aksesoris Meja Kerja Ergonomis',
                'category' => 'Hobi & Lifestyle',
                'amount' => 480000,
                'date' => Carbon::create($year, $month, 20)->toDateString(),
                'notes' => 'Wrist rest & cable management tray',
            ]);

            // 4. Surplus Investment Allocations
            // Financial Investments
            InvestmentAllocation::create([
                'user_id' => $user->id,
                'type' => InvestmentAllocation::TYPE_FINANCIAL,
                'title' => 'Saham BBCA (Bank Central Asia)',
                'category' => 'Saham',
                'platform' => 'Stockbit',
                'amount' => 4500000,
                'date' => Carbon::create($year, $month, 26)->toDateString(),
                'target_objective' => 'DCA dividen compounding jangka panjang 10 tahun',
                'status' => 'active',
                'notes' => 'Beli 4 lot saat koreksi sehat',
            ]);

            InvestmentAllocation::create([
                'user_id' => $user->id,
                'type' => InvestmentAllocation::TYPE_FINANCIAL,
                'title' => 'Bitcoin (BTC) Dollar-Cost Averaging',
                'category' => 'Crypto',
                'platform' => 'Tokocrypto',
                'amount' => 2500000,
                'date' => Carbon::create($year, $month, 27)->toDateString(),
                'target_objective' => 'Akumulasi hard asset digital untuk siklus halving',
                'status' => 'active',
                'notes' => 'Transfer otomatis ke cold wallet storage',
            ]);

            InvestmentAllocation::create([
                'user_id' => $user->id,
                'type' => InvestmentAllocation::TYPE_FINANCIAL,
                'title' => 'Reksa Dana Pasar Uang Bahana',
                'category' => 'Reksa Dana',
                'platform' => 'Bibit',
                'amount' => 1500000,
                'date' => Carbon::create($year, $month, 28)->toDateString(),
                'target_objective' => 'Penebalan dana darurat liquid instan',
                'status' => 'active',
                'notes' => 'Yield stabil 5.2% p.a net',
            ]);

            // Skill / "Leher ke Atas" Investments
            InvestmentAllocation::create([
                'user_id' => $user->id,
                'type' => InvestmentAllocation::TYPE_SKILL,
                'title' => 'Langganan HackTheBox Pro Lab & CPTS',
                'category' => 'Kursus / Lab Cybersecurity',
                'platform' => 'HackTheBox',
                'amount' => 1850000,
                'date' => Carbon::create($year, $month, 11)->toDateString(),
                'target_objective' => 'Menyelesaikan Certified Penetration Testing Specialist (CPTS) Q4 2026',
                'status' => 'in_progress',
                'notes' => 'Lab Active Directory attacks dan pivoting',
            ]);

            InvestmentAllocation::create([
                'user_id' => $user->id,
                'type' => InvestmentAllocation::TYPE_SKILL,
                'title' => 'Voucher Ujian Sertifikasi CompTIA Security+',
                'category' => 'Sertifikasi Internasional',
                'platform' => 'Pearson VUE',
                'amount' => 2200000,
                'date' => Carbon::create($year, $month, 16)->toDateString(),
                'target_objective' => 'Memegang kredensial internasional untuk akselerasi promosi Senior AppSec',
                'status' => 'planned',
                'notes' => 'Jadwal tes direncanakan akhir bulan',
            ]);

            InvestmentAllocation::create([
                'user_id' => $user->id,
                'type' => InvestmentAllocation::TYPE_SKILL,
                'title' => 'Paket Buku: "Designing Data-Intensive Apps" & "System Design"',
                'category' => 'Buku / Literatur',
                'platform' => 'Periplus / Gramedia',
                'amount' => 620000,
                'date' => Carbon::create($year, $month, 22)->toDateString(),
                'target_objective' => 'Memahami arsitektur distributed system & database indexing mendalam',
                'status' => 'completed',
                'notes' => 'Buku fisik hard cover referensi meja',
            ]);
        }
    }
}
