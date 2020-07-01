<?php

namespace App\Utilities;

use App\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Glide\Filesystem\FileNotFoundException;
use League\Glide\Responses\LaravelResponseFactory;
use League\Glide\Server;
use Symfony\Component\HttpFoundation\Response;

/**
 * @todo Store the cached files on Spaces and then redirect so it can be served
 *       from the CDN servers.
 */
class ImageCache
{
    /**
     * The request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The Glide server.
     *
     * @var \App\Utilities\ImageCacheServer
     */
    protected $server;

    /**
     * Construct an image cache instance.
     *
     * @param \App\Utilities\ImageCacheServer
     */
    public function __construct(ImageCacheServer $server, Request $request)
    {
        $this->server = $server;
        $this->request = $request;
    }

    /**
     * Return an image response, using a local fallback image if not found.
     *
     * @param  string  $path
     * @param  array  $params
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getImageResponse(
        string $path,
        array $params = []
    ): Response {
        $responseFactory = new LaravelResponseFactory($this->request);
        $this->server->setResponseFactory($responseFactory);
        try {
            return $this->server->getImageResponse($path, $params);
        } catch (FileNotFoundException $e) {
            $localServer = clone $this->server;
            $localServer->setSource(new Filesystem(new Local('/')));
            $path = config('imagecache.fallbackImage');
            return $localServer->getImageResponse($path, $params);
        }
    }

    /**
     * Return the canonical image response for a model, using a local fallback
     * image if not found.
     *
     * This is expected to be called from a controller and will constrain the
     * parameters as follows:
     *
     * request input('dpr'): 1-4
     * preset: only those specified in config/imagecache presets
     * format: jpg, webp
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $preset
     * @param  array  $format
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getModelImageResponse(
        Model $model,
        string $preset,
        string $format
    ): Response {
        $params = [];
        if (array_key_exists($preset, $this->server->getPresets())) {
            $params['p'] = $preset;
        }
        if ($format === 'webp') {
            $params['fm'] = 'webp';
        } else {
            $params['fm'] = 'pjpg';
        }
        if ($this->request->has('dpr')) {
            $params['dpr'] = min(max(intval($this->request->input('dpr')), 1), 4);
        }
        $path = $model->getRelativeImagePathWithDefaults();
        return $this->getImageResponse($path, $params);
    }

    /**
     * Return the server.
     *
     * @return \App\Utilities\ImageCacheServer
     */
    public function getServer(): ImageCacheServer
    {
        return $this->server;
    }

    /**
     * Return a /image-cache/.. URL for a media path.
     *
     * @param  string  $mediaPath
     * @param  int  $width
     * @param  int  $height
     * @return string
     */
    public static function getMediaUrl(
        string $mediaPath,
        ?int $width = null,
        ?int $height = null
    ): string {
        if (Str::startsWith($mediaPath, '/media/')) {
            $mediaPath = substr($mediaPath, 7);
        }
        $encodedPath = Util::urlEncodePath($mediaPath);
        if ($width || $height) {
            $params = '?';
            if ($width) {
                $params .= 'w=' . $width;
            }
            if ($height) {
                $params .= 'h=' . $height;
            }
        } else {
            $params = '';
        }
        return url('image-cache/' . $encodedPath . $params);
    }
}
