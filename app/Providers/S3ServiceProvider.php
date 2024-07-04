<?php

use Illuminate\Support\ServiceProvider;
use Aws\S3\S3Client;

class S3ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('s3', function () {
            return new S3Client([
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
                'region' => env('AWS_DEFAULT_REGION'),
                'version' => 'latest',
            ]);
        });
    }
}
