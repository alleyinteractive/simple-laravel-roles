<?php

namespace Alley\SimpleRoles\Tests\Fixtures;

use Alley\SimpleRoles\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasRoles;
    protected $guarded = [];
}
