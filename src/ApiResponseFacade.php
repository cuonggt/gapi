<?php

namespace Gtk\Gapi;

use Illuminate\Support\Facades\Facade;

class ApiResponseFacade extends Facade
{
    /**
     * Get the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'api-response';
    }
}