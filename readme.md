# Simple Laravel Roles

This package adds very simple role/capability functionality to a Laravel
application, where the roles and capabilities are defined using a config file.

Once roles and capabilities are defined and a role is assigned to a user,
capabilities can be checked using Laravel's built-in authorization functionality:

```php
if ($user->can('edit_posts')) {
    // Cool.
}
```

## Installation

Via Composer

```bash
$ composer require alleyinteractive/simple-laravel-roles
```

## Setup and Configuration

1. Once the package is installed, you can publish the package files using artisan:
    ```bash
    php artisan vendor:publish --provider="Alley\SimpleRoles\SimpleRolesServiceProvider"
    ```
    This will add two files to your application:

    * `config/roles.php` is where you'll define your roles.
    * `database/migrations/<date>_add_roles_to_users_table.php` adds a `roles` column to the `users` table. Delete this file if it doesn't apply to your use case.
2. Configure your roles and capabilities in `config/roles.php`.
3. Add the `roles` column to any models that will have roles.
4. Add the `Alley\SimpleRoles\HasRoles` trait to any models that will have roles.

## Usage

### In Gates and Policies

This package is intended to augment Laravel's built-in authorization
functionality by adding role and capability buckets in which to add users or
other models. These buckets can be checked during your Gate or Policy checks.

Here's an example Policy method that leverages capabilities to allow deleting a
post if the user owns the post or the user is allowed to delete others' posts:

```php
use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Determine if the given post can be deleted by the user.
 *
 * @param  \App\Models\User  $user
 * @param  \App\Models\Post  $post
 * @return \Illuminate\Auth\Access\Response
 */
public function delete(User $user, Post $post)
{
    return $user->id === $post->user_id || $user->can('delete_others_posts')
        ? Response::allow()
        : Response::deny('You do not own this post.');
}
```

### In place of Gates and Policies

Depending on the complexity of your application, this package could even replace
your gate and policy checks. It will [intercept all Gate checks before other authorization checks](https://laravel.com/docs/8.x/authorization#intercepting-gate-checks)
and if the user has the given ability as a capability in any of their roles, the
check will pass. With this, Laravel's core authorization functionality will work
as usual, and capabilities will be checked instead of defined gates or policies.
In other words, if a user has a role with the `delete_posts` capability, that
capability can be checked using:

```php
if ($user->can('delete_posts')) { /* ... */ }
```

As well as in blade templates:

```php
@can('delete_posts')
  // ...
@endcan
```

The `HasRoles` trait also includes some helpers to check capabilities and roles:

* `hasCapability(string $capability): bool`: Check if the object has the given capability.
* `hasRole(string $role): bool`: Check if the object has the given role.
* `getRoles(): Collection`: Get the roles for the object as Role objects.
* `setRoles(array $roles): bool`: Set the object's roles.
* `addRole(string $role): bool`: Append a role to the object.
* `removeRole(string $role): bool`: Remove a role from the object.

Further, the package provides a `Role` class which can be used to check
capabilities on a specific role.

```php
$contributor = new Role('contributor');
if ($contributor->can('create_posts')) { /* ... */ }
```

## Change log

See the [changelog](changelog.md).

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email the author email (found in `composer.json`) instead of using the issue tracker.

## Credits

- [Matthew Boynes][@mboynes]

## License

MIT. Please see the [license file](license.md) for more information.
