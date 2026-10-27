<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EditProfile;
use App\Filament\Pages\Beranda;
use App\Http\Middleware\EnsurePasswordChanged;
use App\Http\Middleware\SetBscContext;
use App\Support\Terms;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->profile(EditProfile::class, isSimple: false)
            ->userMenuItems(['profile' => fn (\Filament\Actions\Action $action) => $action->visible(fn (): bool => ! auth()->user()?->isGuestVisitor())])
            ->multiFactorAuthentication(
                [AppAuthentication::make()->recoverable()->brandName(config('bsc.brand_name'))],
                isRequired: fn (): bool => (bool) config('bsc.mfa_required')
                    && ! auth()->user()?->isGuestVisitor()
                    && (bool) auth()->user()?->roles->pluck('slug')->intersect(['super-admin', 'admin-sistem', 'pimpinan', 'pengawas'])->isNotEmpty(),
            )
            ->brandName(config('bsc.brand_name'))
            ->brandLogo(asset('images/kinera-logo.svg'))
            ->darkModeBrandLogo(asset('images/kinera-logo-dark.svg'))
            ->brandLogoHeight('2.4rem')
            ->favicon(asset('images/favicon.svg'))
            ->colors([
                'primary' => Color::generateV3Palette('#1F4E8C'),  // biru Rucisoft
                'danger' => Color::generateV3Palette('#D32F2F'),   // aksen merah
                'warning' => Color::generateV3Palette('#C88A12'),
                'success' => Color::generateV3Palette('#2E7D4F'),
                'info' => Color::generateV3Palette('#2A8C9E'),
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->navigationGroups([
                NavigationGroup::make('Umum'),
                NavigationGroup::make('Panduan'),
                NavigationGroup::make(Terms::get('brahman'))->collapsed(),
                NavigationGroup::make(Terms::get('atman'))->collapsed(),
                NavigationGroup::make(Terms::get('karma_phala')),
                NavigationGroup::make(Terms::get('punarbhawa')),
                NavigationGroup::make(Terms::get('moksa'))->collapsed(),
                NavigationGroup::make('Administrasi')->collapsed(),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([Beranda::class])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->renderHook(PanelsRenderHook::TOPBAR_START, fn () => Blade::render('<x-arah.context-badge />'))
            ->renderHook(PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, fn () => Blade::render('<x-arah.guest-login />'))
            ->renderHook(PanelsRenderHook::CONTENT_START, fn () => Blade::render('<x-arah.guest-banner /><x-arah.demo-banner />'))
            ->renderHook(PanelsRenderHook::FOOTER, fn () => Blade::render('<x-arah.footer />'))
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                SetBscContext::class,
                EnsurePasswordChanged::class,
            ]);
    }
}
