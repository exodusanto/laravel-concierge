<?php

namespace Exodusanto\Concierge;

use Illuminate\Support\Str;

trait RefreshApiToken
{
    /**
     * The column name of the "api_token" token.
     *
     * @var string
     */
    protected $apiToken = 'api_token';

    /**
     * The column name of the "api_token_refreshed_at" timestamp.
     *
     * @var string
     */
    protected $apiTokenRefreshedAt = 'api_token_refreshed_at';

    /**
     * Refresh the api token
     *
     * @return void
     */
    public function refreshApiToken()
    {
        $this->{$this->apiToken} = Str::random(60);
        $this->{$this->apiTokenRefreshedAt} = now();

        $this->save();
    }

    /**
     * Revoke the api token by assigning null value
     *
     * @return void
     */
    public function revokeApiToken()
    {
        $this->{$this->apiToken} = null;
        $this->{$this->apiTokenRefreshedAt} = null;

        $this->save();
    }
}
