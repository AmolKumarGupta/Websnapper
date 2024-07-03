<?php

use App\Services\Drive;

return [

    "google-drive" => fn ($id) => Drive::init($id),

];