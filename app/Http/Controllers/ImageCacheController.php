<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\Request;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\Server;
use League\Glide\ServerFactory;
use Symfony\Component\HttpFoundation\Response;

class ImageCacheController extends Controller
{
    /**
     * Return a resized or cached image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $path
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function image(Request $request, string $path): Response
    {
        $server = $this->getGlideServer();
        return $server->getImageResponse($path, [
            'w' => $request->input('w'),
            'h' => $request->input('h'),
            'fit' => $request->input('fit'),
        ]);
    }

    /**
     * Return a Glide server instance.
     *
     * @return \League\Glide\Server
     */
    protected function getGlideServer(): Server
    {
        if (config('filesystems.disks.spaces.url')) {
            $client = new S3Client([
                'credentials' => [
                    'key'    => config('filesystems.disks.spaces.key'),
                    'secret' => config('filesystems.disks.spaces.secret'),
                ],
                'region' => config('filesystems.disks.spaces.region'),
                'version' => 'latest',
                'endpoint' => config('filesystems.disks.spaces.endpoint'),
            ]);
            $sourceAdapter = new AwsS3Adapter($client,
                config('filesystems.disks.spaces.bucket'), 'media',
                ['ACL' => 'public-read']);
        } else {
            $sourceAdapter = new Local(public_path('media'));
        }
        $source = new Filesystem($sourceAdapter);

        $cache = new Filesystem(new Local(storage_path('app/image-path')));

        return ServerFactory::create([
            'source' => $source,
            'cache' => $cache,
            'response' => new LaravelResponseFactory(app('request')),
        ]);
    }
}
