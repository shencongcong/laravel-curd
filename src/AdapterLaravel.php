<?php
/**
 * Created by PhpStorm.
 * User: danielshen
 * Date: 2019/12/9
 * Time: 11:13
 */

namespace Shencongcong\LaravelCurd;

use Event;
use Shencongcong\LaravelCurd\Exceptions\HttpException;

trait AdapterLaravel
{

    /**
     * laravel 5.7及以前用 Event::fire 5.8及以后用 Event::dispatch
     * @param string $version
     * @param        $eventName
     * @param        $data
     *
     * @author danielshen
     * @datetime   2019-12-09 11:38
     * @return mixed
     * @throws \Shencongcong\LaravelCurd\Exceptions\HttpException
     */
    public function laravelEvent($version = '', $eventName, $data)
    {
        $version = $this->getBigVersion($version);
        if ( $version <= 5.7 ) {
           return Event::fire($eventName, $data);
        }
        else {
          return  Event::dispatch($eventName, $data);
        }
    }

    private function getBigVersion($version)
    {
        if ( strlen($version) <= 3 ) {
            throw new HttpException("laravel's version Exception version is ". $version);
        }

        return substr($version, 0, 3);
    }
}