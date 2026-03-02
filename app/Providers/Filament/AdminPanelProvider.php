<?php

namespace App\Providers\Filament;

use App\Http\Middleware\RedirectBasedOnUserType;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\View;
use Filament\Navigation\NavigationGroup;



class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('erp')
            ->path('erp')
            ->login()
            ->profile(isSimple: false)
            ->passwordReset()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn() => View::make('filament.login.custom')
            )
            ->profile()
            ->colors([
                'primary' => Color::hex('#00e676'),
                'gray'    => Color::Zinc,
                'danger'  => Color::Rose,
                'info'    => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            //->font('Montserrat')
            // 3. BRANDING (LOGO Y FAVICON)
            ->brandLogo(asset('images/logo-operacion-sistemica-light.webp'))
            ->darkModeBrandLogo(asset('images/logo-operacion-sistemica-dark.webp'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.svg'))

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
             ->resources([
                //
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Gestión de Garantías')
                    ->icon('heroicon-o-shield-check') // Otro icono sería 'heroicon-o-wrench-screwdriver'
                    ->collapsed(),

                NavigationGroup::make()
                    ->label('Gestión Disciplinaria')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Recursos Humanos')
                    ->icon('heroicon-o-briefcase')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('sistema')
                    ->collapsed(),


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
                RedirectBasedOnUserType::class //Redirige al usuario
            ])
            ->topNavigation() //Habilitar la barra de navegación superior

            //->sidebarCollapsibleOnDesktop()

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
