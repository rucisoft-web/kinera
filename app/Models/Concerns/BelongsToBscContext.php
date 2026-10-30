<?php

namespace App\Models\Concerns;

use App\Models\AnalysisPeriod;
use App\Models\Company;
use App\Support\BscContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Menyaring setiap query berdasarkan instansi & perioda aktif,
 * dan mengisi company_id / analysis_period_id otomatis saat membuat data.
 */
trait BelongsToBscContext
{
    public static function bootBelongsToBscContext(): void
    {
        static::addGlobalScope('bsc_context', function (Builder $builder) {
            $ctx = app(BscContext::class);
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
            $ctx = app(BscContext::class);
            $model->company_id ??= $ctx->companyId();
            $model->analysis_period_id ??= $ctx->periodId();
            if (property_exists($model, 'sequencePrefix') && static::$sequencePrefix && empty($model->code)) {
                $model->code = \App\Support\BscSequence::next(static::$sequencePrefix, $model->company_id, $model->analysis_period_id);
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
