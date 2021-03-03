<?php

namespace Alley\SimpleRoles;

use Illuminate\Support\Collection;
use Alley\SimpleRoles\Contracts\Role as RoleContract;

class Role implements RoleContract
{
    /**
     * Role name.
     *
     * @var string
     */
    protected $role;

    /**
     * Capabilities for this role.
     *
     * @var Collection
     */
    protected $capabilities;

    public function __construct(string $role)
    {
        $this->role = $role;
        $this->capabilities = app('roles')->getCapabilitiesForRole($role);
    }

    /**
     * Get a list of the role's capabilities.
     *
     * @return array
     */
    public function capabilities(): array
    {
        return $this->capabilities->all();
    }

    /**
     * Determine if the role has the given capabilities.
     *
     * @param  iterable|string  $capabilities
     * @return bool
     */
    public function can($capabilities): bool
    {
        return $this->capabilities->contains('*')
            || collect($capabilities)->every(function ($capability) {
                return $this->capabilities->contains($capability);
            });
    }

    /**
     * Determine if the role does not have the given capabilities.
     *
     * @param  iterable|string  $capabilities
     * @return bool
     */
    public function cannot($capabilities): bool
    {
        return ! $this->can($capabilities);
    }
}
