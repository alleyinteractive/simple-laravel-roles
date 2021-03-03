<?php

namespace Alley\SimpleRoles;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;

class SimpleRolesServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the service the package provides.
        $this->app->singleton('roles', function ($app) {
            return new RolesService($app->config['roles']);
        });

        // Add capability checking to the Gate.
        app(Gate::class)->before(function (Authorizable $user, string $capability) {
            if (method_exists($user, 'hasCapability')) {
                return $user->hasCapability($capability) ?: null;
            }
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['roles'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/roles.php' => config_path('roles.php'),
        ], 'roles.config');

        $this->publishes([
            __DIR__ . '/../migrations/add_roles_to_users_table.php' => database_path(
                sprintf('migrations/%s_add_roles_to_users_table.php', date('Y_m_d_His'))
            ),
        ]);
    }
}
