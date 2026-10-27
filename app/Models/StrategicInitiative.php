<?php

namespace App\Models;

use App\Models\Concerns\Auditable;
use App\Models\Concerns\BelongsToBscContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StrategicInitiative extends Model
{
    use Auditable, BelongsToBscContext;

    public const STATUSES = [
        'direncanakan' => 'Direncanakan',
        'berjalan' => 'Berjalan',
        'tertunda' => 'Tertunda',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    protected $guarded = ['id'];

    protected static ?string $sequencePrefix = 'INI';

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'budget_planned' => 'float',
            'progress' => 'float',
        ];
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(StrategicTheme::class, 'strategic_theme_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function objectives(): BelongsToMany
    {
        return $this->belongsToMany(StrategicObjective::class, 'initiative_objective');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(InitiativeActivity::class)->orderBy('due_date');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(InitiativeMilestone::class)->orderBy('due_date');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(InitiativeBudget::class);
    }

    public function findings(): HasMany
    {
        return $this->hasMany(Finding::class);
    }

    public function recalculateProgress(): void
    {
        $progress = app(\App\Services\InitiativeProgressService::class)->calculate($this);
        $this->forceFill(['progress' => $progress])->saveQuietly();
    }

    public function getShortCodeAttribute(): ?string
    {
        return \App\Support\Fmt::shortCode($this->code);
    }

    public function getLabelAttribute(): string
    {
        return ($this->short_code ? $this->short_code.' · ' : '').$this->title;
    }

    public function isOwnedBy(?User $user): bool
    {
        return $user && (int) $this->owner_id === (int) $user->id;
    }
}
