<?php

namespace Exodusanto\Concierge\Tests\Stubs;

use Illuminate\Foundation\Auth\User as BaseUser;
use Exodusanto\Concierge\Contracts\RefreshApiTokenContract;
use Exodusanto\Concierge\RefreshApiToken;

class CustomUser extends BaseUser implements RefreshApiTokenContract
{
    use RefreshApiToken;

    /**
     * Get the name of the "api_token" column
     *
     * @return string
     */
    public function getApiTokenName()
    {
        return 'token';
    }

    /**
     * Get the name of the "api_token_refreshed_at" column
     *
     * @return string
     */
    public function getApiTokenRefreshedAtName()
    {
        return 'token_updated_at';
    }

}
