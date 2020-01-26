<?php

namespace Exodusanto\Concierge\Tests;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Laravel\Nova\Tests\Fixtures\User;
use Exodusanto\Concierge\Tests\TestCase;
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
    public function no_user_auth_skip()
    {
        $this->setConfig();
        $this->createAndHandleRequest();

        $this->assertTrue(true);
    }

    protected function createAndHandleRequest()
    {
        $request = Request::create('/', 'GET');

        $middleware = new RefreshApiToken;

        $middleware->handle($request, function () {
        });
    }

    /**
     * Create and login user
     *
     * @param string $class
     * @return User
     */
    protected function loginUser($class = null)
    {
        $user = $this->createUser($class);
        $this->actingAs($user);

        return $user;
    }
}
