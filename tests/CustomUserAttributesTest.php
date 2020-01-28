<?php

namespace Exodusanto\Concierge\Tests;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Exodusanto\Concierge\Tests\TestCase;
use Exodusanto\Concierge\Tests\Stubs\User;
use Exodusanto\Concierge\Tests\Stubs\CustomUser;
use Exodusanto\Concierge\Http\Middleware\RefreshApiToken;
use Exodusanto\Concierge\Tests\Stubs\UserWithoutContract;

class CustomUserAttributesTest extends TestCase
{
    /** @test */
    public function create_token()
    {
        $this->setConfig();
        $user = $this->loginUser(CustomUser::class);

        $this->createAndHandleRequest();

        $this->assertNotEquals(null, $user->fresh()->token);
    }

    /** @test */
    public function refresh_token()
    {
        $this->setConfig();
        $user = $this->loginUser(CustomUser::class);

        $user->refreshApiToken();
        $token = $user->fresh()->token;

        Carbon::setTestNow(now()->addHour(1));

        $this->createAndHandleRequest();

        $this->assertNotEquals($token, $user->fresh()->token);
    }

    /** @test */
    public function refresh_token_no_timeout_skip()
    {
        $this->setConfig();
        $user = $this->loginUser(CustomUser::class);

        $user->refreshApiToken();
        $token = $user->fresh()->token;

        Carbon::setTestNow(now()->addMinutes(10));

        $this->createAndHandleRequest();

        $this->assertEquals($token, $user->fresh()->token);
    }

    protected function createAndHandleRequest($method = 'GET')
    {
        $request = Request::create('/', $method);

        $middleware = new RefreshApiToken;

        $middleware->handle($request, function () {
        });
    }

    protected function setAuthProviderConfig()
    {
        Config::set('auth.providers.custom_users', [
            'driver' => 'eloquent',
            'model' => CustomUser::class,
        ]);
    }

    protected function setConciergeTokenLifetime()
    {
        Config::set('concierge.tokens_lifetime.custom_users', 60 * 60); // 1h
    }
}
