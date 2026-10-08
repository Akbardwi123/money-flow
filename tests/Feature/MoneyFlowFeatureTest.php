<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Income;
use App\Models\InvestmentAllocation;
use App\Models\User;
use App\Services\FinancialSurplusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoneyFlowFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('MoneyFlow');
        $response->assertSee('Hub Alokasi Investasi');
    }

    public function test_demo_login_authenticates_user_and_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'user@moneyflow.test',
            'name' => 'Aditya Pratama',
        ]);

        $response = $this->post('/demo-login');

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/dashboard');
    }

    public function test_surplus_service_calculates_realtime_metrics_accurately(): void
    {
        $user = User::factory()->create();
        $service = app(FinancialSurplusService::class);

        // 1. Incomes: 20,000,000
        Income::factory()->create([
            'user_id' => $user->id,
            'amount' => 15000000,
            'date' => '2026-10-01',
        ]);
        Income::factory()->create([
            'user_id' => $user->id,
            'amount' => 5000000,
            'date' => '2026-10-05',
        ]);

        // 2. Expenses Priority: 6,000,000
        Expense::factory()->create([
            'user_id' => $user->id,
            'type' => Expense::TYPE_PRIORITY,
            'amount' => 6000000,
            'date' => '2026-10-02',
        ]);

        // Expenses Flexible: 2,000,000
        Expense::factory()->create([
            'user_id' => $user->id,
            'type' => Expense::TYPE_FLEXIBLE,
            'amount' => 2000000,
            'date' => '2026-10-03',
        ]);

        // Total Expenses: 8,000,000
        // Gross Surplus: 20,000,000 - 8,000,000 = 12,000,000

        // 3. Allocations:
        // Financial: 5,000,000
        InvestmentAllocation::factory()->create([
            'user_id' => $user->id,
            'type' => InvestmentAllocation::TYPE_FINANCIAL,
            'amount' => 5000000,
            'date' => '2026-10-10',
        ]);

        // Skill: 3,000,000
        InvestmentAllocation::factory()->create([
            'user_id' => $user->id,
            'type' => InvestmentAllocation::TYPE_SKILL,
            'amount' => 3000000,
            'date' => '2026-10-12',
        ]);

        // Total Allocated: 8,000,000
        // Unallocated Surplus Remaining: 12,000,000 - 8,000,000 = 4,000,000

        $metrics = $service->calculateForPeriod($user, 10, 2026);

        $this->assertEquals(20000000.0, $metrics['total_income']);
        $this->assertEquals(6000000.0, $metrics['priority_expenses']);
        $this->assertEquals(2000000.0, $metrics['flexible_expenses']);
        $this->assertEquals(8000000.0, $metrics['total_expenses']);
        $this->assertEquals(12000000.0, $metrics['gross_surplus']);
        $this->assertEquals(5000000.0, $metrics['financial_investment']);
        $this->assertEquals(3000000.0, $metrics['skill_investment']);
        $this->assertEquals(8000000.0, $metrics['total_allocated']);
        $this->assertEquals(4000000.0, $metrics['remaining_surplus']);
        $this->assertEquals(60.0, $metrics['surplus_rate']); // 12m / 20m * 100
        $this->assertEquals('healthy', $metrics['status']);
    }

    public function test_user_can_create_income_via_http(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/incomes', [
            'title' => 'Gaji Pokok Engineer',
            'source' => 'Gaji Pokok',
            'amount' => 12000000,
            'date' => '2026-10-01',
            'notes' => 'Pemasukan rutin',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('incomes', [
            'user_id' => $user->id,
            'title' => 'Gaji Pokok Engineer',
            'amount' => 12000000,
        ]);
    }

    public function test_user_can_create_priority_and_flexible_expenses(): void
    {
        $user = User::factory()->create();

        // Priority expense
        $response1 = $this->actingAs($user)->post('/expenses', [
            'type' => Expense::TYPE_PRIORITY,
            'title' => 'Sewa Kos Bulanan',
            'category' => 'Tempat Tinggal',
            'amount' => 2500000,
            'date' => '2026-10-02',
            'notes' => 'Wajib per tanggal 2',
        ]);
        $response1->assertRedirect();

        // Flexible expense
        $response2 = $this->actingAs($user)->post('/expenses', [
            'type' => Expense::TYPE_FLEXIBLE,
            'title' => 'Ngopi di Coffee Shop',
            'category' => 'Kuliner & Nongkrong',
            'amount' => 85000,
            'date' => '2026-10-03',
            'notes' => 'Working session santai',
        ]);
        $response2->assertRedirect();

        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'type' => 'priority',
            'title' => 'Sewa Kos Bulanan',
        ]);

        $this->assertDatabaseHas('expenses', [
            'user_id' => $user->id,
            'type' => 'flexible',
            'title' => 'Ngopi di Coffee Shop',
        ]);
    }

    public function test_user_can_allocate_surplus_to_financial_and_skill_investments(): void
    {
        $user = User::factory()->create();

        // Financial allocation
        $response1 = $this->actingAs($user)->post('/allocations', [
            'type' => InvestmentAllocation::TYPE_FINANCIAL,
            'title' => 'Saham BBCA',
            'category' => 'Saham',
            'platform' => 'Stockbit',
            'amount' => 3000000,
            'date' => '2026-10-15',
            'target_objective' => 'DCA dividen tahunan',
            'status' => 'active',
        ]);
        $response1->assertRedirect();

        // Skill allocation (Investasi Leher ke Atas)
        $response2 = $this->actingAs($user)->post('/allocations', [
            'type' => InvestmentAllocation::TYPE_SKILL,
            'title' => 'Lab HackTheBox Pro CPTS',
            'category' => 'Kursus / Lab Cybersecurity',
            'platform' => 'HackTheBox',
            'amount' => 1500000,
            'date' => '2026-10-16',
            'target_objective' => 'Lulus sertifikasi CPTS akhir tahun',
            'status' => 'in_progress',
        ]);
        $response2->assertRedirect();

        $this->assertDatabaseHas('investment_allocations', [
            'user_id' => $user->id,
            'type' => 'financial',
            'title' => 'Saham BBCA',
        ]);

        $this->assertDatabaseHas('investment_allocations', [
            'user_id' => $user->id,
            'type' => 'skill',
            'title' => 'Lab HackTheBox Pro CPTS',
            'status' => 'in_progress',
        ]);
    }

    public function test_reports_page_and_csv_export_work(): void
    {
        $user = User::factory()->create();

        Income::factory()->create([
            'user_id' => $user->id,
            'title' => 'Gaji',
            'amount' => 10000000,
            'date' => '2026-10-01',
        ]);

        $response = $this->actingAs($user)->get('/reports');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Portofolio', false);
        $response->assertSee('Riwayat Mutasi Dana Terintegrasi', false);

        $csvResponse = $this->actingAs($user)->get('/reports/export?year=2026&month=10');
        $csvResponse->assertStatus(200);
        $this->assertEquals('text/csv; charset=UTF-8', $csvResponse->headers->get('Content-Type'));
    }
}
