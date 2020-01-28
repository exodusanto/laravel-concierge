<?php

namespace Exodusanto\Concierge\Contracts;

interface RefreshApiTokenContract
{
    /**
     * Get the name of the "api_token" column
     *
     * @return string
     */
    public function getApiTokenName();

    /**
     * Get the api_token of the user
     *
     * @return string|null
     */
    public function getApiToken();

    /**
     * Get the name of the "api_token_refreshed_at" column
     *
     * @return string
     */
    public function getApiTokenRefreshedAtName();

    /**
     * Get the api_token_refreshed_at timestamp
     *
     * @return Carbon|null
     */
    public function getApiTokenRefreshedAt();

    /**
     * Refresh the api token
     *
     * @return void
     */
    public function refreshApiToken();

    /**
     * Revoke the api token
     *
     * @return void
     */
    public function revokeApiToken();
}
