<?php

namespace Exodusanto\Concierge\Tests\Stubs;

use Illuminate\Foundation\Auth\User as BaseUser;

class UserWithoutContract extends BaseUser {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
}
