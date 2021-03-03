<?php

namespace Alley\SimpleRoles;

/**
 * HasRoles trait for Users.
 */
trait HasRoles
{
    public function getRoles() {
        return collect($this->roles)->map(function($role) {
            return new Role($role);
        });
    }

    public function hasRole($role) {
        return in_array($role, $this->roles, true);
    }

    public function hasCapability($capability) {
        foreach ($this->getRoles() as $role) {
            if ($role->can($capability)) {
                return true;
            }
        }

        return false;
    }
}
