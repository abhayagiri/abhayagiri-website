<?php

namespace App\Utilities;

use Aws\S3\S3Client;
use League\Glide\Server;
use League\Glide\ServerFactory;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3V3\AwsS3V3Adapter;
use League\Flysystem\Local\LocalFilesystemAdapter;

class ImageCacheServer extends Server
{
    /**
     * Construct a Glide server for ImageCache.
     */
    public function __construct($type = 'local')
    {
        $factory = new ServerFactory();
        parent::__construct(
            $this->createMediaFilesystem($type),
            $this->createCacheFilesystem(),
            $factory->getApi()
        );
        $this->setPresets(config('imagecache.presets'));
    }

    /**
     * Create a filesystem for local cache files.
     */
    protected function createCacheFilesystem(): Filesystem
    {
        return new Filesystem(new LocalFilesystemAdapter(config('imagecache.cacheDir')));
    }

    /**
     * Create an adapter for media files on the local filesystem.
     */
    protected function createLocalMediaAdapter(): LocalFilesystemAdapter
    {
        return new LocalFilesystemAdapter(public_path('media'));
    }

    /**
     * Create a filesystem for media files.
     *
     * @return \League\Flysystem\Filesystem
     */
    protected function createMediaFilesystem(string $type): Filesystem
    {
        if ($type === 'spaces') {
            $adapter = $this->createSpacesMediaAdapter();
        } elseif ($type === 'local') {
            $adapter = $this->createLocalMediaAdapter();
        } else {
            throw new InvalidArgumentException('Unexpected type: ' . $type);
        }
        return new Filesystem($adapter);
    }

    /**
     * Create an adapter for media files in Digital Ocean Spaces.
     *     */
    protected function createSpacesMediaAdapter(): AwsS3V3Adapter
    {
        $client = new S3Client([
            'credentials' => [
                'key'    => config('filesystems.disks.spaces.key'),
                'secret' => config('filesystems.disks.spaces.secret'),
            ],
            'region' => config('filesystems.disks.spaces.region'),
            'version' => 'latest',
            'endpoint' => config('filesystems.disks.spaces.endpoint'),
        ]);
        return new AwsS3V3Adapter(
            client: $client,
            bucket: config('filesystems.disks.spaces.bucket'),
            prefix: 'media'
        );
    }
}
