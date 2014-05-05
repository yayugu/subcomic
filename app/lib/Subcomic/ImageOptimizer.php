<?php

namespace Subcomic;

class ImageOptimizer
{

    /**
     * @param string $imageBlob
     * @return string
     */
    public static function optimizeWithUserAgent($imageBlob)
    {
        if (self::shouldResize($imageBlob)) {
            return $imageBlob;
        }
        $pixel = self::resizeWidthAndHeight();
        $image = new Image($imageBlob, $pixel, $pixel);
        return $image->getBlob();
    }

    /**
     * @param string $imageBlob
     * @return int
     */
    public static function shouldResize($imageBlob)
    {
        return strlen($imageBlob) < self::resizeBorderBlobSize();
    }

    /**
     * @return int
     */
    public static function resizeBorderBlobSize()
    {
        return \Agent::isMobile()
            ? 1024 * 1024      // 1MB
            : 1024 * 1024 * 2; // 2MB
    }

    /**
     * @return int
     */
    public static function resizeWidthAndHeight()
    {
        if (\Agent::isMobile()) {
            return 1136;
        }
        return 2048;
    }

}