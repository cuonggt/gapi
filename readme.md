# Gapi

[![Build Status](https://travis-ci.org/gtkvn/gapi.svg?branch=v0.1.0)](https://travis-ci.org/gtkvn/gapi)
[![Latest Stable Version](https://poser.pugx.org/gtk/gapi/v/stable)](https://packagist.org/packages/gtk/gapi)
[![Total Downloads](https://poser.pugx.org/gtk/gapi/downloads)](https://packagist.org/packages/gtk/gapi)
[![License](https://poser.pugx.org/gtk/gapi/license)](https://packagist.org/packages/gtk/gapi)

Gapi provides a simple, convenient way to handle your API response properly.

## Install

Install via composer - edit your `composer.json` to require the package.

    "require": {
        "gtk/gapi": "dev-master"
    }

Then run `composer update` in your terminal to pull it in.

Once this has finished, you will need to add the service provider to the providers array in your `config/app.php` file as follows:

    'providers' => [
        // Other service providers...

        Gtk\Gapi\GapiServiceProvider::class,
    ],

If you are using Lumen, register the `Gtk\Gapi\GapiServiceProvider` in your `bootstrap/app.php` file:

    // $app->register(App\Providers\AppServiceProvider::class);
    // $app->register(App\Providers\AuthServiceProvider::class);
    // $app->register(App\Providers\EventServiceProvider::class);
    $app->register(Gtk\Gapi\GapiServiceProvider::class);
    
## Basic Usage

Next, you are ready to response API results for your application with `api_response()` helper function:

    class FooController extends Controlelr
    {
        // some code ...

        return api_response()->json([
            'success' => true,
        ]);
    }

## License

Gapi is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
