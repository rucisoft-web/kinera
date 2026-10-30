<?php

namespace App\Support;

use App\Filament\Resources;

/**
 * Bantuan pengisian per layar: teori, cara berpikir, panduan kolom, contoh, dan tangkapan layar isian.
 * Isi tiap topik ada di resources/bantuan/{kunci}.php; gambar di public/images/bantuan/{kunci}.jpg.
 */
class Bantuan
{
    /** @return array<string, array{0: string, 1: string, 2: class-string}> kunci => [judul, kelompok menu, resource] */
    public static function registry(): array
    {
        return [
            'pemindaian-lingkungan' => ['Pemindaian Lingkungan', 'brahman', Resources\EnvironmentScans\EnvironmentScanResource::class],
            'pestel' => ['PESTEL', 'brahman', Resources\PestelAnalyses\PestelAnalysisResource::class],
            'analisis-industri' => ['Analisis Industri (Lima Kekuatan Porter)', 'brahman', Resources\IndustryForces\IndustryForceResource::class],
            'pasar-pelanggan' => ['Pasar & Pelanggan', 'brahman', Resources\MarketAnalyses\MarketAnalysisResource::class],
            'analisis-pesaing' => ['Analisis Pesaing', 'brahman', Resources\Competitors\CompetitorResource::class],
            'analisis-regulasi' => ['Analisis Regulasi', 'brahman', Resources\Regulations\RegulationResource::class],
            'pemangku-kepentingan' => ['Pemangku Kepentingan', 'brahman', Resources\Stakeholders\StakeholderResource::class],
            'isu-strategis' => ['Isu Strategis', 'brahman', Resources\StrategicIssues\StrategicIssueResource::class],
            'swot' => ['Analisis SWOT', 'brahman', Resources\SwotItems\SwotItemResource::class],
            'tows' => ['Strategi TOWS', 'brahman', Resources\TowsStrategies\TowsStrategyResource::class],
            'jati-diri' => ['Jati Diri Instansi (Visi, Misi, Nilai)', 'atman', Resources\AtmanProfiles\AtmanProfileResource::class],
            'kapabilitas' => ['Kapabilitas', 'atman', Resources\Capabilities\CapabilityResource::class],
            'kontrak-kinerja' => ['Kontrak Kinerja BLU', 'karma_phala', Resources\PerformanceContracts\PerformanceContractResource::class],
            'tema-strategis' => ['Tema Strategis', 'karma_phala', Resources\StrategicThemes\StrategicThemeResource::class],
            'pilihan-strategis' => ['Pilihan Strategis', 'karma_phala', Resources\StrategicChoices\StrategicChoiceResource::class],
            'sasaran-strategis' => ['Sasaran Strategis', 'karma_phala', Resources\StrategicObjectives\StrategicObjectiveResource::class],
            'relasi-sasaran' => ['Relasi Sasaran (Sebab-Akibat)', 'karma_phala', Resources\ObjectiveRelations\ObjectiveRelationResource::class],
            'indikator-kinerja' => ['Indikator Kinerja', 'karma_phala', Resources\Kpis\KpiResource::class],
            'target-ik' => ['Target IK', 'karma_phala', Resources\KpiTargets\KpiTargetResource::class],
            'realisasi-ik' => ['Realisasi IK', 'karma_phala', Resources\KpiActuals\KpiActualResource::class],
            'tinjauan-kinerja' => ['Tinjauan Kinerja', 'karma_phala', Resources\PerformanceReviews\PerformanceReviewResource::class],
            'inisiatif' => ['Inisiatif Strategis', 'punarbhawa', Resources\StrategicInitiatives\StrategicInitiativeResource::class],
            'aktivitas-inisiatif' => ['Aktivitas Inisiatif', 'punarbhawa', Resources\InitiativeActivities\InitiativeActivityResource::class],
            'milestone-inisiatif' => ['Milestone Inisiatif', 'punarbhawa', Resources\InitiativeMilestones\InitiativeMilestoneResource::class],
            'anggaran-inisiatif' => ['Anggaran Inisiatif', 'punarbhawa', Resources\InitiativeBudgets\InitiativeBudgetResource::class],
            'temuan' => ['Temuan', 'punarbhawa', Resources\Findings\FindingResource::class],
            'tindakan-korektif' => ['Tindakan Korektif', 'punarbhawa', Resources\CorrectiveActions\CorrectiveActionResource::class],
            'penilaian-kematangan' => ['Penilaian Kematangan', 'moksa', Resources\MaturityAssessments\MaturityAssessmentResource::class],
            'ketergantungan' => ['Ketergantungan Strategis', 'moksa', Resources\StrategicDependencies\StrategicDependencyResource::class],
            'pelembagaan' => ['Pelembagaan', 'moksa', Resources\Institutionalizations\InstitutionalizationResource::class],
        ];
    }

    /** Kunci topik untuk layar yang sedang dibuka (render hook scopes berisi kelas halaman & resource). */
    public static function keyFor(array $scopes): ?string
    {
        foreach (static::registry() as $key => [, , $resource]) {
            if (in_array($resource, $scopes, true)) {
                return $key;
            }
        }

        return null;
    }

    public static function exists(?string $key): bool
    {
        return $key !== null && isset(static::registry()[$key]) && is_file(resource_path("bantuan/{$key}.php"));
    }

    /** @return array<string, mixed> */
    public static function topic(string $key): array
    {
        [$title, $group] = static::registry()[$key];

        return ['key' => $key, 'title' => $title, 'group' => Terms::get($group)] + require resource_path("bantuan/{$key}.php");
    }

    public static function image(string $key): ?string
    {
        return is_file(public_path("images/bantuan/{$key}.jpg")) ? asset("images/bantuan/{$key}.jpg").'?v=1' : null;
    }
}
