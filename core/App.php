<?php

namespace liveticket\core;

use Exception;

class App
{
    private static $container = array();

    public static function bind($key, $value)
    {
        static:: $container [$key] = $value;
    }

    public static function get($key)
    {
        if(! array_key_exists ($key, static:: $container ))
        {
            throw new Exception("No se ha encontrado la clave $key en el contenedor");
        }
        return static:: $container [$key];
    }
}
