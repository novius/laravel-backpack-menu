# Laravel Backoffice Menu
[![Travis](https://img.shields.io/travis/novius/laravel-menu.svg?maxAge=1800&style=flat-square)](https://travis-ci.org/novius/laravel-menu)
[![Packagist Release](https://img.shields.io/packagist/v/novius/laravel-menu.svg?maxAge=1800&style=flat-square)](https://packagist.org/packages/novius/laravel-menu)
[![Licence](https://img.shields.io/packagist/l/novius/laravel-menu.svg?maxAge=1800&style=flat-square)](https://github.com/novius/laravel-menu#licence)

Manages the editing and rendering of menus


## TODO !

- [ ] Rename ServiceProvider ;
- [ ] Update composer.json (keywords, authors, require, extra...) ;
- [ ] Remove this TODO section.
- [ ] Create slug on saving a menu.
- [ ] Save local on saving an item.
- [ ] Order items list by locale.
- [ ] Create Menu::display($slug, $locale).


## Installation

In your terminal:

```sh
composer require novius/laravel-menu
```

In `config/app.php`, add:

```php
Novius\Menu\ServiceProvider::class,
```

## Usage & Features

TODO


## Testing

Run the tests with:

```sh
./test.sh
```


## Lint

Run php-cs with:

```sh
./cs.sh
```


## Contributing

Contributions are welcome!
Leave an issue on Github, or create a Pull Request.


## Licence

This package is under [GNU Affero General Public License v3](http://www.gnu.org/licenses/agpl-3.0.html) or (at your option) any later version.
