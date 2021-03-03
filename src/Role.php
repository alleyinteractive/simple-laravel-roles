<?php

namespace Alley\SimpleRoles;

use Illuminate\Support\Collection;
use Alley\SimpleRoles\Contracts\Role as RoleContract;

class Role implements RoleContract
{
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
        $this->capabilities = collect(config("roles.{$this->role}"));
    }

    public function capabilities()
    {
        return $this->capabilities->all();
    }

    /**
     * Determine if the role has the given capabilities.
     *
     * @param  iterable|string  $capabilities
     * @return bool
     */
    public function can($capabilities)
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
    public function cannot($capabilities)
    {
        return ! $this->can($capabilities);
    }
}
