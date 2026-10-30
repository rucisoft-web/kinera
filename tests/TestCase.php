<?php

namespace Tests;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Services\Import\BscTemplate;
use App\Support\BscContext;
use Database\Seeders\BscRolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    /** Tanpa Mockery: output artisan tidak di-mock. */
    public $mockConsoleOutput = false;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(BscRolePermissionSeeder::class);
    }

    protected function company(string $code, int $year = 2026): array
    {
        $company = Company::create(['code' => $code, 'name' => 'PT '.$code, 'is_active' => true]);
        $period = AnalysisPeriod::create(['company_id' => $company->id, 'code' => (string) $year, 'name' => 'Tahun '.$year, 'year' => $year,
            'start_date' => $year.'-01-01', 'end_date' => $year.'-12-31', 'status' => 'berjalan', 'is_active' => true]);

        return [$company, $period];
    }

    protected function user(string $email, string $role, ?Company $company): User
    {
        $user = User::create(['name' => Str::before($email, '@'), 'email' => $email, 'password' => 'Rahasia-12345', 'is_active' => true, 'must_change_password' => false]);
        if ($company) {
            $user->companies()->attach($company->id);
        }
        $user->roles()->attach(Role::where('slug', $role)->value('id'), ['company_id' => $role === 'super-admin' ? null : $company?->id]);

        return $user->fresh();
    }

    /** Workbook sementara dari baris per sheet (urutan kolom = BscWorkbook::SHEETS). */
    protected function workbook(array $sheets): string
    {
        return app(BscTemplate::class)->withRows(sys_get_temp_dir().'/ruci-test-'.Str::random(8).'.xlsx', $sheets);
    }

    protected function useContext(AnalysisPeriod $period): void
    {
        app(BscContext::class)->set($period->company_id, $period->id);
    }
}
