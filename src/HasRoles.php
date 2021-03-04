<?php

namespace Alley\SimpleRoles;

use Illuminate\Support\Collection;

/**
 * HasRoles trait for models.
 */
trait HasRoles
{
    /**
     * Initialize the trait.
     */
    public function initializeHasRoles()
    {
        // Cast the $roles attribute to an array.
        $this->casts['roles'] = 'array';
    }

    /**
     * Check if the model has the specified capability. This will return true if
     * any of the model's roles can perform the action.
     *
     * @param string $capability Capability.
     * @return bool
     */
    public function hasCapability(string $capability): bool
    {
        foreach ($this->getRoles() as $role) {
            if ($role->can($capability)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all the roles for the current model as a collection of Role objects.
     *
     * @return Collection
     */
    public function getRoles(): Collection
    {
        return collect($this->roles)->map(function($role) {
            return new Role($role);
        });
    }

    /**
     * Check if the model has the given role.
     *
     * @param string $role Role name, as defined in the roles config.
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles ?? [], true);
    }

    /**
     * Set the model's roles.
     *
     * @param array $roles Roles to save to the database for this model.
     * @return bool
     */
    public function setRoles(array $roles): bool
    {
        $roles = array_unique($roles);
        return $this->forceFill(compact('roles'))->save();
    }

    /**
     * Append a valid role to the model's roles.
     *
     * Only valid roles defined in the config can be appended using this method.
     *
     * @param string $role Role name, as defined in the roles config.
     * @return bool
     */
    public function addRole(string $role): bool
    {
        if (app('roles')->roleExists($role)) {
            return $this->setRoles(array_merge($this->roles ?? [], [$role]));
        }

        return false;
    }

    /**
     * Remove the given role from the user's set of roles.
     *
     * @param string $role Role name, as defined in the roles config.
     * @return bool
     */
    public function removeRole(string $role): bool
    {
        return $this->setRoles(
            array_filter($this->roles ?? [], function($existingRole) use ($role) {
                return $role !== $existingRole;
            })
        );
    }
}
