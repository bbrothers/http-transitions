# http-transitions

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Tests][ico-tests]][link-tests]
[![Total Downloads][ico-downloads]][link-downloads]

A package for transitioning HTTP requests and responses based on a version header.  

Release updates to your API schema without breaking existing clients by creating `Transition` classes that transition the request and/or response to match the previously expected result. Each layer of transitions only needs to convert the current version to match the previous version, from there the request and response will be piped through the subsequent layers until they match the version requested in the `Api-Version` header.

Largely based on [Stripe's API versioning](https://stripe.com/blog/api-versioning) article.

## Requirements

- PHP 8.3+
- Laravel 11, 12 or 13

## Install

``` bash
$ composer require bbrothers/http-transitions
```
The service provider is auto-discovered.

#### Middleware

In `bootstrap/app.php`, add the middleware globally or to a group:
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->api(append: [
        Transitions\TransitionMiddleware::class,
    ]);
})
```

#### Publish Config
```bash
php artisan vendor:publish --tag=transitions-config
```

## Usage
Add version numbers and an array of `Transition` classes to the `transitions.php` file.

``` php
return [
   'headerKey' => 'Api-Version',
   'transitions'    => [
       '20160101' => [
           FullNameToNameTransition::class,
           NameToFirstNameLastNameTransition::class,
           BirthDateTransition::class,
       ],
       '20150101' => [
           FirstNameLastNameToFullNameTransition::class,
       ],
   ],
]
```
Create a transition:
```php
class NameToFirstNameLastNameTransition extends Transition
{
    public function transformResponse(Response $response): Response
    {
        $content = json_decode($response->getContent(), true);
        $content = array_diff_key(array_merge($content, $content['name']), ['name' => true]);

        return $response->setContent(json_encode($content));
    }
}
```

## Transition Generators
```bash
$ php artisan make:transition NameToFirstNameLastNameTransition
```
Optionally, the `--request-only` or `--response-only` flags can be added to create a transform that only generates a `transformRequest` or `transformResponse` method respectively.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/bbrothers/http-transitions.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-tests]: https://img.shields.io/github/actions/workflow/status/bbrothers/http-transitions/tests.yml?branch=master&style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bbrothers/http-transitions.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/bbrothers/http-transitions
[link-tests]: https://github.com/bbrothers/http-transitions/actions/workflows/tests.yml
[link-downloads]: https://packagist.org/packages/bbrothers/http-transitions
[link-author]: https://github.com/bbrothers
[link-contributors]: ../../contributors
