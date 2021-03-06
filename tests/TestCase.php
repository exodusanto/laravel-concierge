<?php

namespace Exodusanto\Concierge\Tests;

use Exodusanto\Concierge\Tests\Stubs\User;
use Orchestra\Testbench\TestCase as TestCaseBase;
use Exodusanto\Concierge\ConciergeServiceProvider;
use Illuminate\Support\Facades\Config;

abstract class TestCase extends TestCaseBase
{
    protected function getPackageProviders($app)
    {
        return [ConciergeServiceProvider::class];
    }

    protected function setUp() : void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    public function createUser($class)
    {
        $user = $class ? new $class : new User;

        $user->forceFill([
            'email' => 'example@domain.com',
            'name' => 'Example',
            'password' => 'secret',
        ])->save();

        return $user;
    }

    /**
     * Create and login user
     *
     * @param string $class
     * @return User
     */
    public function loginUser($class = null)
    {
        $user = $this->createUser($class);
        $this->actingAs($user);

        return $user;
    }

    public function setConfig($onlyAuth = false)
    {
        $this->setAuthProviderConfig();

        if (!$onlyAuth) {
            $this->setConciergeTokenLifetime();
        }
    }

    protected function setAuthProviderConfig()
    {
        Config::set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => User::class,
        ]);
    }

    protected function setConciergeTokenLifetime()
    {
        Config::set('concierge.tokens_lifetime.users', 60 * 60); // 1h
    }
}
