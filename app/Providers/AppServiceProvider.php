<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        Gate::define('user-management', function ($user) {
            return $user->hasPermission('user-management');
        });

        Gate::define('import-data', function ($user) {
            return $user->hasAnyImportPermission();
        });

        // AdminLTE menu config
        // AdminLTE menu config
        $importTypes = config('import-types');
        $menu = config('adminlte.menu');

        $importedDataMenu = [
            'text' => 'Imported Data',
            'icon' => 'fas fa-fw fa-chart-line',
            'submenu' => [],
        ];

        foreach ($importTypes as $typeKey => $typeConfig) {
            foreach ($typeConfig['files'] as $fileKey => $fileConfig) {
                $importedDataMenu['submenu'][] = [
                    'text' => "{$typeConfig['label']} - {$fileConfig['label']}",
                    'url' => url("/imports/{$typeKey}/{$fileKey}"),
                ];
            }
        }

        // Find the position where "Imported Data" should be inserted
        $insertPosition = 2; // Position after "Data Import"

        // Insert the dynamic menu at the desired position
        array_splice($menu, $insertPosition, 0, [$importedDataMenu]);

        // Update the menu configuration
        config(['adminlte.menu' => $menu]);
    }
}
