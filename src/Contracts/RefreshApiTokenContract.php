<?php

namespace Exodusanto\Concierge\Contracts;

interface RefreshApiTokenContract
{
    public function refreshApiToken();
    public function revokeApiToken();
}
