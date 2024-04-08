<?php

namespace App\Services\Contract;

interface Service {
    
    public static function init($userId): Service;

    public function save($model);

}