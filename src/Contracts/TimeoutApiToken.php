<?php

namespace Exodusanto\Concierge\Contracts;

interface TimeoutApiToken
{
    public function refreshApiToken();
    public function revokeApiToken();
}
