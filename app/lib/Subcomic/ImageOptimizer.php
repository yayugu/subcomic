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
        $resizeBorderSize = \Agent::isMobile()
            ? 1024 * 1024
            : 1024 * 1024 * 2;
        if (strlen($imageBlob) < $resizeBorderSize) {
            return $imageBlob;
        }
        $image = new Image($imageBlob);
        $image->resize();
        return $image->getBlob();
    }

}