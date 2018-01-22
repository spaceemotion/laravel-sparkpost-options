# laravel-sparkpost-options

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

This package adds support for adding SparkPost messaging options. Even though Laravel allows to set global options,
this package adds the functionality for per-message options via the X-MSYS-API header, even when using the
integrated "send via API" implementation.

**SparkPost Options documentation:**
https://developers.sparkpost.com/api/smtp-api.html#header-using-the-x-msys-api-custom-header

## Install

Via Composer

``` bash
$ composer require spaceemotion/laravel-sparkpost-options
```

**5.4 and below:** Add the ServiceProvider to your `app.php`: 

```php
Spaceemotion\LaravelSparkPostOptions\SparkPostConfigProvider::class,
```

## Usage

You can either attach the mail header directly:
```php
$mailable->withSwiftMessage(function ($message) use ($options) {
    $message->getHeaders()->addTextHeader(SparkPostConfigProvider::CONFIG_HEADER, json_encode([
        // Your options here...
    ]));
});
```

or via the integrated `attach` method:

```php
ConfigurableTransport::attach($mailable, [
    // Your options here...
]);
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/spaceemotion/laravel-sparkpost-options.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/spaceemotion/laravel-sparkpost-options.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/spaceemotion/laravel-sparkpost-options
[link-downloads]: https://packagist.org/packages/spaceemotion/laravel-sparkpost-options
[link-author]: https://github.com/spaceemotion
