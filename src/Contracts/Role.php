<?php

namespace Alley\SimpleRoles\Contracts;

interface Role {
    /**
     * Determine if the role has the given capabilities.
     *
     * @param  iterable|string  $capabilities
     * @return bool
     */
    public function can($capabilities);

    /**
     * Determine if the role does not have the given capabilities.
     *
     * @param  iterable|string  $capabilities
     * @return bool
     */
    public function cannot($capabilities);
}
