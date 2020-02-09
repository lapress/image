<?php

namespace LaPress\Image;

use Illuminate\Routing\Controller;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class ImagesController extends Controller
{
    /**
     * @var ImageService
     */
    private $imageService;

    /**
     * @param ImageService $imageService
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * @param int    $width
     * @param int    $height
     * @param string $method
     * @param string $path
     */
    public function show(int $width, int $height, string $method, string $path)
    {
        if (!in_array($method, config('images.allowed_modification_methods'))) {
            return abort(404);
        }

        return $this->imageService->serve($path, $width, $height, $method);
    }
}
