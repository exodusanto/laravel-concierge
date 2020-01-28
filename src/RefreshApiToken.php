<?php

namespace Exodusanto\Concierge;

use Carbon\Carbon;
use Illuminate\Support\Str;

trait RefreshApiToken
{
    /**
     * Get the name of the "api_token" column
     *
     * @return string
     */
    public function getApiTokenName()
    {
        return 'api_token';
    }

    /**
     * Get the api_token of the user
     *
     * @return string|null
     */
    public function getApiToken()
    {
        return $this->{$this->getApiTokenName()};
    }

    /**
     * Get the name of the "api_token_refreshed_at" column
     *
     * @return string
     */
    public function getApiTokenRefreshedAtName()
    {
        return 'api_token_refreshed_at';
    }

    /**
     * Get the api_token of the user
     *
     * @return Carbon|null
     */
    public function getApiTokenRefreshedAt()
    {
        return $this->{$this->getApiTokenRefreshedAtName()};
    }

    /**
     * Generate a new token
     *
     * @return string
     */
    protected function generateNewToken()
    {
        return Str::random(60);
    }

    /**
     * Refresh the api token
     *
     * @return void
     */
    public function refreshApiToken()
    {
        $token = $this->generateNewToken();

        $this->{$this->getApiTokenName()} = $token;
        $this->{$this->getApiTokenRefreshedAtName()} = now();

        $this->save();
    }

    /**
     * Revoke the api token
     *
     * @return void
     */
    public function revokeApiToken()
    {
        $this->{$this->getApiTokenName()} = null;
        $this->{$this->getApiTokenRefreshedAtName()} = null;

        $this->save();
    }
}
