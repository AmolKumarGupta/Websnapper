<?php

namespace App\Services;

use Google\Client;
use Google\Service\Oauth2;
use App\Services\Contract\Service;
use Google\Service\Drive as GoogleDrive;

class Drive implements Service {

    public static function init(): Drive {
        $service = app()->make(Drive::class);

        $client = new Client();
        $client->setAuthConfig(storage_path("app/private/google_cred.json"));
        $client->setAccessType("offline");

        $client->addScope(GoogleDrive::DRIVE);
        $client->addScope(Oauth2::USERINFO_EMAIL);
        $client->addScope(Oauth2::USERINFO_PROFILE);

        $client->setRedirectUri(route('oauth.callback'));


        return $service;
    }

}