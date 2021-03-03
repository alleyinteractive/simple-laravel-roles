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

    /**
     * Initialize the roles service.
     *
     * @param array $roles
     */
    public function __construct(array $roles)
    {
        $this->roles = collect($roles);
    }

    /**
     * Get the configured capabilities for a given role.
     *
     * @param string $role Role name.
     * @return Collection
     */
    public function getCapabilitiesForRole(string $role): Collection
    {
        return collect($this->roles->get($role));
    }

    /**
     * Check if the given role exists.
     *
     * @param string $role Role name.
     * @return bool
     */
    public function roleExists(string $role): bool
    {
        return $this->roles->has($role);
    }
}
