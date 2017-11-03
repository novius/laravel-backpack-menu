# Laravel Backoffice Menu
[![Travis](https://img.shields.io/travis/novius/laravel-menu.svg?maxAge=1800&style=flat-square)](https://travis-ci.org/novius/laravel-menu)
[![Packagist Release](https://img.shields.io/packagist/v/novius/laravel-menu.svg?maxAge=1800&style=flat-square)](https://packagist.org/packages/novius/laravel-menu)
[![Licence](https://img.shields.io/packagist/l/novius/laravel-menu.svg?maxAge=1800&style=flat-square)](https://github.com/novius/laravel-menu#licence)

Manages the editing and rendering of menus in a Laravel - Backpack application.


## Installation

In your terminal:

```sh
composer require novius/laravel-backpack-menu
```

In `config/app.php`, add:

```php
Novius\Backpack\Menu\MenuServiceProvider::class,
```

Execute:
```bash
php artisan vendor:publish --provider="Novius\Backpack\Menu\MenuServiceProvider" --tag="routes"
php artisan vendor:publish --provider="Novius\Backpack\Menu\MenuServiceProvider" --tag="lang"
php artisan vendor:publish --provider="Novius\Backpack\Menu\MenuServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Novius\Backpack\Menu\MenuServiceProvider" --tag="views"
php artisan vendor:publish --provider="Novius\Backpack\Menu\MenuServiceProvider" --tag="config"

php artisan migrate
```

## Usage & Features

You can display the menu within your view like this:

```php
// The function takes two parameters:
//  1. Slug => Identifies the menu
//  2. Locale => (Optional) Force a locale version of the menu.

\Novius\Backpack\Menu\Models\Menu::display('header', 'en');
```

To achieve that, first you need:

#### Configure the package. Take a look at the comments in:
```
/config/laravel-backpack-menu.php
```

#### Use the trait _LinkedItems_ in the models you listed in the _linkableObjects_ configuration

```php
use Novius\Backpack\Menu\LinkedItems;

// And optionally overrides the base functionality to suit your needs:

public static function linkableItems(string $prefix = ''){}
public function linkableUrl(){}
public function linkableTitle(){}
```

#### Add a link in your sidebar.blade.php file to get access from the backpack backoffice

```html
<li>
  <a href="{{ route('crud.menu.index') }}">
      <i class="fa fa-list"></i>
      <span>{{ trans('laravel-backpack-menu::menu.menus') }}</span>
  </a>
</li>
```

#### Create your menus and items. You can reorder and nest the items. The items are related to the current locale. Switch the back-office language to add items for other locales.

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
  
