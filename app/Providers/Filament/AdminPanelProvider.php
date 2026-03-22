<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\GantiPassword;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\KeuanganBulanIniWidget;
use App\Filament\Widgets\TransaksiTerbaruWidget;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    private function getViteAsset(string $entry): string
    {
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        return 'build/' . ($manifest[$entry]['file'] ?? 'assets/app.css');
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->assets([
                Css::make('app-css', public_path($this->getViteAsset('resources/css/app.css'))),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->renderHook('panels::body.end', fn () => new \Illuminate\Support\HtmlString('
                <script>
                    function syncSidebar() {
                        if (!window.Alpine || !Alpine.store("sidebar")) return;
                        const isPOS = window.location.pathname.includes("pos-kasir");
                        isPOS ? Alpine.store("sidebar").close() : Alpine.store("sidebar").open();
                    }
                    document.addEventListener("DOMContentLoaded", syncSidebar);
                    document.addEventListener("livewire:navigated", syncSidebar);
                </script>
            '))
            ->userMenuItems([
                MenuItem::make()
                    ->label('Ganti Password')
                    ->icon(Heroicon::OutlinedLockClosed)
                    ->url(fn () => GantiPassword::getUrl()),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                DashboardStatsWidget::class,
                KeuanganBulanIniWidget::class,
                TransaksiTerbaruWidget::class,
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
            ]);
    }
}
