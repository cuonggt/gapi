# Gapi

[![Build Status](https://travis-ci.org/gtkvn/gapi.svg?branch=v0.1.0)](https://travis-ci.org/gtkvn/gapi)
[![Latest Stable Version](https://poser.pugx.org/gtk/gapi/v/stable)](https://packagist.org/packages/gtk/gapi)
[![Total Downloads](https://poser.pugx.org/gtk/gapi/downloads)](https://packagist.org/packages/gtk/gapi)
[![License](https://poser.pugx.org/gtk/gapi/license)](https://packagist.org/packages/gtk/gapi)

Gapi provides a simple, convenient way to handle your API response properly.

## Install

To get started with Gapi, use Composer to add the package to your project's dependencies:

    composer require gtkvn/gapi

Next, register the `Gtk\Gapi\GapiServiceProvider` in your `config/app.php` file:

    'providers' => [
        // Other service providers...

        Gtk\Gapi\GapiServiceProvider::class,
    ],

## Basic Usage

Next, you are ready to response API results for your application with `api_response()` helper function:

    class FooController extends Controlelr
    {
        // some code ...

        return api_response()->withArray([
            'success' => true,
        ]);
    }

## License

The Gapi is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
