<?php

namespace Exodusanto\Concierge\Tests;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Exodusanto\Concierge\Tests\TestCase;
use Exodusanto\Concierge\Tests\Stubs\User;
use Exodusanto\Concierge\Http\Middleware\RefreshApiToken;
use Exodusanto\Concierge\Tests\Stubs\UserWithoutContract;

class TestMiddleware extends TestCase
{
    /** @test */
    public function create_token()
    {
        $this->setConfig();
        $user = $this->loginUser();

        $this->createAndHandleRequest();

        $this->assertNotEquals(null, $user->fresh()->api_token);
    }

    /** @test */
    public function refresh_token()
    {
        $this->setConfig();
        $user = $this->loginUser();

        $user->refreshApiToken();
        $token = $user->fresh()->api_token;

        Carbon::setTestNow(now()->addHour(1));

        $this->createAndHandleRequest();

        $this->assertNotEquals($token, $user->fresh()->api_token);
    }

    /** @test */
    public function refresh_token_no_timeout_skip()
    {
        $this->setConfig();
        $user = $this->loginUser();

        $user->refreshApiToken();
        $token = $user->fresh()->api_token;

        Carbon::setTestNow(now()->addMinutes(10));

        $this->createAndHandleRequest();

        $this->assertEquals($token, $user->fresh()->api_token);
    }

    /** @test */
    public function user_without_contract_skip()
    {
        $this->setConfig();
        $user = $this->loginUser(UserWithoutContract::class);

        $this->createAndHandleRequest();

        $this->assertEquals(null, $user->fresh()->api_token);
    }

    /** @test */
    public function no_concierge_config_skip()
    {
        $this->setConfig(true);
        $user = $this->loginUser();

        $this->createAndHandleRequest();

        $this->assertEquals(null, $user->fresh()->api_token);
    }

    /** @test */
    public function post_request_skip()
    {
        $this->setConfig();
        $user = $this->loginUser();

        $this->createAndHandleRequest('POST');

        $this->assertEquals(null, $user->fresh()->api_token);
    }

    /** @test */
    public function no_user_auth_skip()
    {
        $this->setConfig();
        $this->createAndHandleRequest();

        $this->assertTrue(true);
    }

    /** @test */
    public function revoke_token()
    {
        $this->setConfig();
        $user = $this->loginUser();

        $user->refreshApiToken();

        $this->assertNotNull($user->fresh()->api_token);

        $user->revokeApiToken();

        $this->assertNull($user->fresh()->api_token);
    }

    protected function createAndHandleRequest($method = 'GET')
    {
        $request = Request::create('/', $method);

        $middleware = new RefreshApiToken;

        $middleware->handle($request, function () {
        });
    }

}
