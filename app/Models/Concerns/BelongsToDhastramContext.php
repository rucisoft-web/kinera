<?php

namespace App\Models\Concerns;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Support\DhastramContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Menyaring setiap query berdasarkan satker BLU & perioda aktif,
 * dan mengisi company_id / analysis_period_id otomatis saat membuat data.
 */
trait BelongsToDhastramContext
{
    public static function bootBelongsToDhastramContext(): void
    {
        static::addGlobalScope('dhastram_context', function (Builder $builder) {
            $ctx = app(DhastramContext::class);
            if ($ctx->isDisabled()) {
                return;
            }
            $table = $builder->getModel()->getTable();
            if ($companyId = $ctx->companyId()) {
                $builder->where($table.'.company_id', $companyId);
            }
            if ($periodId = $ctx->periodId()) {
                $builder->where($table.'.analysis_period_id', $periodId);
            }
        });

        static::creating(function ($model) {
            $ctx = app(DhastramContext::class);
            $model->company_id ??= $ctx->companyId();
            $model->analysis_period_id ??= $ctx->periodId();
            if (property_exists($model, 'sequencePrefix') && static::$sequencePrefix && empty($model->code)) {
                $model->code = \App\Support\DhastramSequence::next(static::$sequencePrefix, $model->company_id, $model->analysis_period_id);
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function analysisPeriod(): BelongsTo
    {
        return $this->belongsTo(AnalysisPeriod::class);
    }
}
