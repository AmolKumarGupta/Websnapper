<?php

namespace App\Services\Contract;

use Closure;

interface ServiceManager {

    public function get(string $name, $userId);

    public function set(string $name, Closure $cb);

}