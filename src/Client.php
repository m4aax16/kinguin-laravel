<?php

namespace m4aax16\kinguin;


class Client
{
    public static $apiKey;
    
    //Build client
    function __construct($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    public static function getKey()
    {
        return self::$apiKey;
    }
}