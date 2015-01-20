PHP HTML MARKUP Generator [![Packagist License](http://img.shields.io/packagist/l/ARCANEDEV/Markup.svg?style=flat-square)](https://github.com/ARCANEDEV/Markup/blob/master/LICENSE)
==============
[![Travis Status](http://img.shields.io/travis/ARCANEDEV/Markup.svg?style=flat-square)](https://travis-ci.org/ARCANEDEV/Markup)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/ARCANEDEV/Markup.svg?style=flat-square)](https://scrutinizer-ci.com/g/ARCANEDEV/Markup/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/ARCANEDEV/Markup.svg?style=flat-square)](https://scrutinizer-ci.com/g/ARCANEDEV/Markup/?branch=master)
[![Github Release](http://img.shields.io/github/release/ARCANEDEV/Markup.svg?style=flat-square)](https://github.com/ARCANEDEV/Markup/releases)
[![Packagist Downloads](https://img.shields.io/packagist/dt/arcanedev/markup.svg?style=flat-square)](https://packagist.org/packages/arcanedev/markup)
[![Github Issues](http://img.shields.io/github/issues/ARCANEDEV/Markup.svg?style=flat-square)](https://github.com/ARCANEDEV/Markup/issues)

*By [ARCANEDEV&copy;](http://www.arcanedev.net/)*

Create HTML tags and render them efficiently.

### Requirements
    
    - PHP >= 5.4.0
    
## Installation

###Composer

You can install the package via [Composer](http://getcomposer.org/). Add this to your `composer.json`:

```json
{
    "require": {
        "arcanedev/markup": "~1.1"
    }
}
```
    
Then install it via `composer install` or `composer update`.

### Laravel

After the installation, include the service provider within `app/config/app.php`.
```php
'providers' => [
    ...
    'Arcanedev\Markup\Laravel\ServiceProvider',
];
```

And finally, add the facade alias to this same file at the bottom:
```php
'aliases' => [
    ...
    'Markup' => 'Arcanedev\Markup\Laravel\Facade',
];
```

## USAGE

To generate your title tag for example :
```php
$title = Markup::title('Hello world');
echo $title->render()

// or 
echo Markup::title('Hello world');

// The result: <title>Hello world</title>
```

And There is more:

```php
echo Markup::img('img/logo.png', 'alt Logo', ['class' => 'logo img-responsive']);
// Result : <img src="img/logo.png" alt="alt Logo" class="logo img-responsive"/> 

echo Markup::meta('property', 'og:description', 'Site web description');
// Result : <meta property="og:description" content="Site web description"/>

echo Markup::link('page/about-us', 'About us', ['class' => 'btn btn-info']);
// Result : <a href="page/about-us" class="btn btn-info">About us</a>

echo Markup::style('assets/css/style.css', ['media' => 'all']);
// Result : <link rel="stylesheet" href="assets/css/style.css" type="text/css" media="all"/>

echo Markup::script('assets/js/app.js');
// Result : <script src="assets/js/app.js"></script>
```

### Contribution

Any ideas are welcome. Feel free the submit any issues or pull requests.

### TODO

  - [ ] Documentation
  - [ ] Examples
  - [x] More tests and code coverage
  - [x] Laravel Support (v4.2)
  - [ ] Laravel Support (v5.0)
  - [ ] Refactoring
  
## License

Markup package is licenced under the [MIT license](https://github.com/ARCANEDEV/Markup/blob/master/LICENSE).
