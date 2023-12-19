<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return !!$user->roles->first();
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            // new Help,
            new \App\Nova\Metrics\OrderTrend,
            new \App\Nova\Metrics\OrderPriceTrend,
            new \App\Nova\Metrics\UserTrend,
            // new \App\Nova\Metrics\BonusPercent,
            // new \App\Nova\Metrics\BonusPartition
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new \Anaseqal\NovaSidebarIcons\NovaSidebarIcons,
            new \Anaseqal\NovaImport\NovaImport,
            new \Yukun\SalesOrder\SalesOrder,
            \Vyuldashev\NovaPermission\NovaPermissionTool::make()
                ->rolePolicy(\App\Policies\RootPolicy::class)
                ->permissionPolicy(\App\Policies\RootPolicy::class),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
