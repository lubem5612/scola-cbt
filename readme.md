# ScolaCbt

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require transave/scola-cbt
```

## Usage
Follow these steps to use the `transave/scola-cbt` package
- publish the scola-cbt.php file to your config folder with this command 
```$xslt
$ php artisan vendor:publish --tag=cbt-config
```
- edit `auth_model`  in `config/scola-cbt.php` to your application's `User` model class like so 
``` bash
 'auth_model' => \App\Models\User::class,
```
- in your User model, add the package user helper class like so
```$xslt
  use Transave\ScolaCbt\Helpers\UserHelper;

  class User extends Authenticable 
  {
       use HasApiTokens, HasFactory, Notifiable;
       use UserHelper;
  }
```
- you may also publish the package assets like so for the frontend to have access to them
```$xslt
 $ php artisan vendor:publish --tag=cbt-assets
```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email lubem@raadaa.com instead of using the issue tracker.

## Credits

- [Lubem Tser][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/transave/scola-cbt.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/transave/scola-cbt.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/transave/scola-cbt/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/transave/scola-cbt
[link-downloads]: https://packagist.org/packages/transave/scola-cbt
[link-travis]: https://travis-ci.org/transave/scola-cbt
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/transave
[link-contributors]: ../../contributors
