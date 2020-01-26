<?php

namespace Exodusanto\Concierge\Tests\Stubs;

use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as BaseUser;
use Exodusanto\Concierge\Contracts\TimeoutApiToken;

class User extends BaseUser implements TimeoutApiToken {

    public function refreshApiToken()
    {
        $this->api_token = Str::random(60);
        $this->api_token_refreshed_at = now();

        $this->save();
    }

    public function revokeApiToken()
    {
        $this->api_token = null;
        $this->api_token_refreshed_at = null;

        $this->save();
    }
}
