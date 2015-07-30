# LaravelAdmin
基于Laravel5.1封装的后台管理系统

To install this package you will need:

- Laravel 5.1+
- PHP 5.5.9+

You must then modify your `composer.json` file and run `composer update` to include the latest version of the package in your project.

```json
"require": {
    "forone/admin": "dev-master"
}
```

Or you can run the `composer require` command from your terminal.

```
composer require forone/admin:dev-master
```

> At this time the package is still in a developmental stage and as such does not have a **stable** release.
> You may need to set your `minimum-stability` to `dev`.

Once the package is installed the next step is dependant on which framework you're using.

### Laravel

Open `config/app.php` and register the required service provider and aliases.

```php
'providers' => [
    Forone\Admin\Providers\ForoneServiceProvider::class
]
```

```php
'aliases' => [
    'Form'      => Illuminate\Html\FormFacade::class,
    'Html'      => Illuminate\Html\HtmlFacade::class,
]
```

If you'd like to make configuration changes in the configuration file you can pubish it with the following Aritsan command:

```
php artisan vendor:publish --provider="Forone\Admin\Providers\ForoneServiceProvider" --force
```

Publishing Defender configuration file and migrations

```
php artisan vendor:publish --provider="Artesaos\Defender\Providers\DefenderServiceProvider"
```

Init data

```
php artisan db:init
```


