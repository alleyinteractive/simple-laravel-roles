<?php

namespace Alley\SimpleRoles\Tests;

use Alley\SimpleRoles\SimpleRolesServiceProvider;
use Alley\SimpleRoles\Tests\Fixtures\User;
use Orchestra\Testbench\TestCase;

abstract class OrchestraTestCase extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [SimpleRolesServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['migrator']->path(__DIR__.'/../database/migrations');

        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $app['config']->set('roles', [
            'admin' => ['*'],
            'content_editor' => ['create_posts', 'edit_posts'],
            'video_editor' => ['create_videos', 'edit_videos'],
            'subscriber' => ['create_comments'],
        ]);
    }

    protected function setupDatabase()
    {
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        // Add `roles` to the users table.
        include_once __DIR__ . '/../database/migrations/2021_03_01_000001_add_roles_to_users_table.php.stub';
        (new \AddRoleToUsersTable)->up();
    }

    protected function createUser($roles = null): User
    {
        return User::create([
            'email' => 'test@test.test',
            'name' => 'Test Testerson',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'roles' => $roles ? (array) $roles : null,
        ]);
    }
}
