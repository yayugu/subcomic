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
        if (self::shouldNotResize($imageBlob)) {
            return $imageBlob;
        }
        $pixel = self::resizeRect();
        $image = new Image($pixel);
        $image->loadBlob($imageBlob);
        return $image->getBlob();
    }

    /**
     * @param string $imageBlob
     * @return int
     */
    public static function shouldNotResize($imageBlob)
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
     * a comic page ratio of width:height is almost 1:sqrt(2).
     * but cut book width is shorter than sqrt(2).
     *
     * @return Rect
     */
    public static function resizeRect()
    {
        $rect = new Rect();
        if (\Agent::isMobile()) {
            $rect->width = 804; // 1136 * sqrt(2)
            $rect->height = 1136; // iPhone5 height
            return $rect;
        }
        $rect->width = 1148;
        $rect->height = 2048;
        return $rect;
    }

    /**
     * for general pre optimization
     *
     * @return Rect
     */
    public static function resizeRectGeneral()
    {
        $rect = new Rect();
        $rect->width = 1148;
        $rect->height = 2048;
        return $rect;
    }

}
