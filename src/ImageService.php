<?php

namespace LaPress\Image;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class ImageService
{
    /**
     * @param string      $path
     * @param int         $width
     * @param int         $height
     * @param string|null $method
     * @return Response
     */
    public function serve(string $path, int $width, int $height, ?string $method)
    {
        $method = $method ?: config('images.default_modification_method', 'fit');

        $manager = new ImageManager(
            config('images.image_manager', [])
        );
        $path = Str::startsWith($path, 'content') ? str_replace('content', 'wp-content', $path) : $path;

        $content = $manager->cache(function ($image) use ($path, $width, $height, $method) {
            abort_unless(
                Storage::disk(config('images.storage'))->exists($path), Response::HTTP_NOT_FOUND
            );

            return $image->make(Storage::disk(config('images.storage'))->get($path))->{$method}($width, $height, function ($constraint) use ($method) {
                if ($method === 'resize' && config('images.keep_aspect_ration_on_resize', true)) {
                    $constraint->aspectRatio();
                }
            })->encode(
                config('images.encoded_image.format', 'jpg'),
                config('images.encoded_image.quality', 75)
            );

        }, config('images.cache.ttl'));


        return $this->buildImageResponse($content);
    }

    /**
     * @param $content
     * @return Response
     */
    public function buildImageResponse($content)
    {
        // define mime type
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);

        // respond with 304 not modified if browser has the image cached
        $etag = md5($content);
        $not_modified = isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag;
        $content = $not_modified ? null : $content;
        $status_code = $not_modified ? 304 : 200;

        // return http response
        return new Response($content, $status_code, [
            'Content-Type'   => $mime,
            'Cache-Control'  => 'max-age=31536000, public',
            'Content-Length' => strlen($content),
            'Etag'           => $etag,
        ]);
    }
}
