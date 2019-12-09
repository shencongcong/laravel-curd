<?php

namespace Shencongcong\LaravelCurd;

use Illuminate\Support\Facades\Facade as LaravelFacade;


class Facade extends LaravelFacade
{

    public static function getFacadeAccessor()
    {
        return 'Shencongcong\LaravelCurd\LaravelCurd';
    }

}
