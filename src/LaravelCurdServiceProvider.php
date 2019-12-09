<?php
/**
 * Created by PhpStorm.
 * User: danielshen
 * Date: 2019/12/5
 * Time: 19:53
 */

namespace Shencongcong\LaravelCurd;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class LaravelCurdServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        $this->app->singleton('laravel-curd', function () {
            return new LaravelCurd($this->app);
        });
    }
}

