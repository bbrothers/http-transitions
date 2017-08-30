# http-transitions

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A package for transitioning HTTP requests and responses based on a version header.  

Release updates to your API schema without breaking existing clients by creating `Transition` classes that transition the request and/or response to match the previously expected result. Each layer of transitions only needs to convert the current version to match the previous version, from there the request and response will be piped through the subsequent layers until they match the version requested in the `Api-Version` header.

Largely based on [Stripe's API versioning](https://stripe.com/blog/api-versioning) article.

## Install

``` bash
$ composer require bbrothers/http-transitions
```
#### Middleware

In your `app/Http/Kernel.php` file, add:
```php
protected $middleware = [
    Transitions\TransitionMiddleware::class
];
```

#### Service Provider

For Laravel 5.4, in your `config/app.php` file, in the `providers` array, add:
```php
Transitions\TransitionProvider::class
```

#### Publish Config
```bash
php artisan vendor:publish --provider="Transitions\TransitionProvider"
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

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/bbrothers/http-transitions.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/bbrothers/http-transitions/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/bbrothers/http-transitions.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/bbrothers/http-transitions.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bbrothers/http-transitions.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/bbrothers/http-transitions
[link-travis]: https://travis-ci.org/bbrothers/http-transitions
[link-scrutinizer]: https://scrutinizer-ci.com/g/bbrothers/http-transitions/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/bbrothers/http-transitions
[link-downloads]: https://packagist.org/packages/bbrothers/http-transitions
[link-author]: https://github.com/bbrothers
[link-contributors]: ../../contributors
