# Laravel concierge

[![Latest Version on Packagist](https://img.shields.io/packagist/v/exodusanto/laravel-concierge.svg?style=flat-square)](https://packagist.org/packages/exodusanto/laravel-concierge)
[![Build Status](https://img.shields.io/travis/exodusanto/laravel-concierge/master.svg?style=flat-square)](https://travis-ci.org/exodusanto/laravel-concierge)
[![Total Downloads](https://img.shields.io/packagist/dt/exodusanto/laravel-concierge.svg?style=flat-square)](https://packagist.org/packages/exodusanto/laravel-concierge)

Extend the [base solution](https://laravel.com/docs/6.x/api-authentication) from Laravel with some new features

- Auto refresh user token on `GET` requests
- Refresh/Revoke methods
- Blade directive

## Installation

You can install the package via composer:

```bash
composer require exodusanto/laravel-concierge
```

## Usage

### 1. Migration
Migrate your user table with `api_token` and `api_token_refreshed_at`.

``` php
Schema::table('users', function (Blueprint $table) {
    $table->string('api_token')->nullable();
    $table->timestamp('api_token_refreshed_at')->nullable();
});
```

#### api_token_refreshed_at
This attribute is use to store the timestamp of `api_token` update

### 2. Model
Add `RefreshApiToken` trait and `RefreshApiTokenContract` contract to your model
``` php
class User extends BaseUser implements RefreshApiTokenContract
{
    use RefreshApiToken;
}
```

### 3. Config

Publish concierge config
```bash
php artisan vendor:publish --tag=concierge-config
```

Use the same key to identify the right model in `auth.providers` and `concierge.tokens_lifetime`
``` php
// config/auth.php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\User::class,
    ],
]

// config/concierge.php
'tokens_lifetime' => [
    'users' => 10800 // 3h
]
```

### 4. Middleware

Append `RefreshApiToken` to your application `web` middleware group
```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        ...
        \Exodusanto\Concierge\Http\Middleware\RefreshApiToken::class,
    ],
```

### @Concierge

Concierge is shipped with a custom Blade directive, it will render the token of the authenticated user
``` twig
@concierge

<!-- Rendered to -->
<script>
    __CONCIERGE__ = { "api_token": "XXXXXXXXXXXX" }
</script>
```

#### @Concierge options
`@concierge($guard, $attributeName)`
``` twig
@concierge('other_guard', 'my_token')

<!-- Rendered to -->
<script>
    <!-- Token of other_guard authenticated user -->
    __CONCIERGE__ = { "my_token": "XXXXXXXXXXXX" }
</script>
```


### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email info@antoniodalsie.com instead of using the issue tracker.

## Credits

- [Antonio Dal Sie](https://github.com/exodusanto)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
