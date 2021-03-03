<?php

namespace Alley\SimpleRoles;

use Illuminate\Support\Collection;

class RolesService
{
    /**
     * Roles collection.
     *
     * @var Collection
     */
    protected $roles;

    public function __construct($roles)
    {
        $this->roles = collect($roles);
    }

    public function getCapabilitiesForRole($role)
    {
        return $this->roles->pluck($role);
    }

    public function roleExists($role)
    {
        return $this->roles->has($role);
    }
}
