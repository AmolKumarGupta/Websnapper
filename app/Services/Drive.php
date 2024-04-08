<?php

namespace App\Services;

use Exception;
use Google\Client;
use App\Models\Video;
use Google\Service\Oauth2;
use Illuminate\Support\Str;
use App\Services\Contract\Service;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;
use App\Models\Service as ServiceModel;
use App\Models\ServiceVideo;
use App\Services\Trait\SetService;
use Google\Service\Drive as GoogleDrive;

class Drive implements Service {
    use SetService;

    public $provider = "google";

    public Client $client;

    public $userId;

    public static function init($userId): Drive 
    {
        $service = app()->make(Drive::class);
        $service->userId = $userId;
        
        $service->client = new Client();
        $service->client->setAuthConfig(storage_path("app/private/google_cred.json"));
        $service->client->setAccessType("offline");

        $service->client->addScope(GoogleDrive::DRIVE);
        $service->client->addScope(Oauth2::USERINFO_EMAIL);
        $service->client->addScope(Oauth2::USERINFO_PROFILE);

        $service->client->setRedirectUri(route('oauth.callback'));
        
        $refreshToken = $service->refreshToken();
        if (!$refreshToken) {
            throw new Exception("refresh token not found");
        }

        $token = $service->client->fetchAccessTokenWithRefreshToken($refreshToken);
        $service->client->setAccessToken($token);

        return $service;
    }

    /**
     * @param Video $video
     */
    public function save($video) 
    {
        $path = $video->getPath();
        $drive = new GoogleDrive($this->client);
        $file = new DriveFile();

        $file->name = Str::slug($video->title) .".". pathinfo($path, PATHINFO_EXTENSION);

        $result = $drive->files->create($file, [
            'data' => file_get_contents($path),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart'
        ]);

        $permission = new Permission();
        $permission->setType('anyone');
        $permission->setRole('reader');
        
        $permission = $drive->permissions->create($result->id, $permission);

        $payload = ["id" => $result->id];
        $sVideo = new ServiceVideo;
        $sVideo->service_id = $this->getService()->id;
        $sVideo->video_id = $video->id;
        $sVideo->payload = json_encode($payload);
        $sVideo->save();

        $file = $drive->files->get($result->id, ['fields' => 'webViewLink']);
        $payload['link'] = $file->getWebViewLink();

        $sVideo->payload = $payload;
        $sVideo->save();
    }

    /**
     * @param Video $video
     * @return string get sharable link of the file
     */
    public function getLink($video) 
    {
        $sVideo = ServiceVideo::where('service_id', $this->getService()->id)
            ->where('video_id', $video->id)
            ->first();

        if (! $sVideo) {
            throw new Exception("No relation found between video and service");
        }

        $payload = json_decode($sVideo->payload, true);
        if (json_last_error()) {
            throw new Exception(json_last_error_msg());
        }

        $fileId = $payload['id'] ?? null;
        if ($fileId == null) {
            throw new Exception("file id not found");
        }

        $drive = new GoogleDrive($this->client);
        $file = $drive->files->get($fileId, ['fields' => 'webViewLink']);
        return $file->getWebViewLink();
    }

    private function refreshToken() 
    {
        $model = $this->getService();
        
        if (! json_validate($model->payload)) {
            throw new Exception("invalid payload");
        }

        $payload = json_decode($model->payload, true);
        return $payload['refresh_token'] ?? null;
    }

}