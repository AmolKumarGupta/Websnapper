<?php

namespace App\Services\Manager;

use App\Services\Contract\ServiceManager as ContractManager;
use Closure;
use Exception;

class ServiceManager implements ContractManager
{

    private static $store;

    public function __construct()
    {
        self::$store = config("websnapper.services");
    }

    public function get(string $name, $userId) 
    {
        $cb = self::$store[$name] ?? null;
        if ( !$cb ) {
            throw new Exception("provider {$name} is not found");
        }

        return $cb($userId);
    }


    public function set(string $name, Closure $cb) 
    {
        self::$store[$name] = $cb;
    }

}