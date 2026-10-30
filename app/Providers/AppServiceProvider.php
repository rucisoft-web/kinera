<?php

namespace App\Providers;

use App\Models\User;
use App\Support\BscContext;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(BscContext::class);
    }

    public function boot(): void
    {
        \Filament\Forms\Components\DatePicker::configureUsing(fn ($c) => $c->native(false)->displayFormat('d/m/Y'));
        \Filament\Forms\Components\DateTimePicker::configureUsing(fn ($c) => $c->native(false)->displayFormat('d/m/Y H:i'));
        \Filament\Tables\Table::configureUsing(fn ($t) => $t->defaultPaginationPageOption(25)->striped());

        FilamentAsset::register([Css::make('kinera', asset('css/arah.css').'?v=9')]);

        // Kebijakan kata sandi: minimal 10 karakter, huruf & angka
        Password::defaults(fn () => Password::min(10)->letters()->numbers());

        // Izin Kinera dapat dipakai lewat Gate/@can: Gate::allows('kpi.approve_actual')
        Gate::before(function (User $user, string $ability) {
            if (str_contains($ability, '.') && array_key_exists($ability, \App\Support\PermissionCatalog::PERMISSIONS)) {
                return $user->hasPermission($ability);
            }

            return null;
        });

        RateLimiter::for('login', fn ($request) => Limit::perMinute(5)->by(($request->input('email') ?? '').'|'.$request->ip()));

        Event::listen(Login::class, function (Login $event) {
            if ($event->user instanceof User) {
                $event->user->forceFill(['last_login_at' => now()])->saveQuietly();
            }
        });

        \App\Support\GuestMode::registerWriteGuard();
    }
}
