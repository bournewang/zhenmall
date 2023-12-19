<?php

return [
    'upload' => [
        'image_limit' => 1024 * 1024
    ],
    'import_log_location' => 'storage/import-log/',
    'per_page' => 20,
    'express_app_code' => env('EXPRESS_APP_CODE')
];