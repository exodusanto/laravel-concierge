<?php

namespace Exodusanto\Concierge\Contracts;

interface RefreshApiTokenContract
{
    public function getApiTokenName();
    public function getApiTokenRefreshedAtName();
    public function refreshApiToken();
    public function revokeApiToken();
}
