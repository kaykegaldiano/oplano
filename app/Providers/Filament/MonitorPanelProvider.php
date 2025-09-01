<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MonitorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('monitor')
            ->path('monitor')
            ->brandName('Painel - Monitor')
            ->colors([
                'primary' => '#A855F7',
                'info' => '#C4B5FD',
                'success' => '#22C55E',
                'warning' => '#F59E0B',
                'danger' => '#EF4444',
            ])
            ->login()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Monitor/Resources'), for: 'App\Filament\Monitor\Resources')
            ->discoverPages(in: app_path('Filament/Monitor/Pages'), for: 'App\Filament\Monitor\Pages')
            ->discoverResources(in: app_path('Filament/Shared/Resources'), for: 'App\Filament\Shared\Resources')
            ->discoverPages(in: app_path('Filament/Shared/Pages'), for: 'App\Filament\Shared\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Monitor/Widgets'), for: 'App\Filament\Monitor\Widgets')
            ->discoverWidgets(in: app_path('Filament/Shared/Widgets'), for: 'App\Filament\Shared\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                'role:monitor'
            ]);
    }
}
