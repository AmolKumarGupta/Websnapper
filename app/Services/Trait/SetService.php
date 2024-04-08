<?php

namespace App\Services\Trait;

use Exception;
use App\Models\Service as ServiceModel;

trait SetService
{
    public ServiceModel $service;

    public function getService()
    {
        if (isset($this->service)) {
            return $this->service;
        }

        $model = ServiceModel::where('provider', $this->provider)
            ->where('user_id', $this->userId)
            ->first();

        if (!$model) {
            throw new Exception("user has no access to this service");
        }

        $this->service = $model;
        return $model;
    }
}
