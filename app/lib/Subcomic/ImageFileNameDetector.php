<?php

namespace Subcomic;

class ImageFileNameDetector
{
    /**
     * @param string $name
     * @return bool
     */
    public static function isImage($name)
    {
        return self::isImageExt($name)
            && self::isNotHiddenFile($name)
            && self::isNotSystemFile($name);
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function isImageExt($name)
    {
        return preg_match('/\.(jpg|jpeg|png|gif|bmp)/i', $name);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected static function isNotHiddenFile($name)
    {
        return !preg_match('/(?:\A\.)|(?:\/\.)/', $name);
    }


    /**
     * @param string $name
     * @return bool
     */
    protected static function isNotSystemFile($name)
    {
        return !preg_match('/(?:\A__MACOSX)|(?:\/__MACOSX)/', $name);
    }
}