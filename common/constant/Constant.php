<?php
namespace common\constant;

abstract class Constant
{
    public static function find($key)
    {
        return constant("static::$key");
    }
}