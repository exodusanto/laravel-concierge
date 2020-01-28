<?php

namespace Exodusanto\Concierge\Tests\Stubs;

use Illuminate\Foundation\Auth\User as BaseUser;
use Exodusanto\Concierge\Contracts\RefreshApiTokenContract;
use Exodusanto\Concierge\RefreshApiToken;

class User extends BaseUser implements RefreshApiTokenContract
{
    use RefreshApiToken;
}
