<?php
/**
 * Created by PhpStorm.
 * User: danielshen
 * Date: 2019/7/10
 * Time: 17:02
 */

namespace Shencongcong\LaravelCurd\Exceptions;

// todo http异常的处理
class HttpException extends Exception
{

    public function __construct(string $message = "", int $code = 0)
    {
        parent::__construct($message, intval($code));
    }
}