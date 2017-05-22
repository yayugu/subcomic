<?php

namespace Subcomic\Archive;

class ArchiveFactory
{
    /**
     * @param string $path
     * @return ArchiveInterface
     * @throws \Exception
     */
    public static function create($path)
    {
        $ext = self::getExtension($path);
        switch ($ext) {
            case 'zip':
                return new Zip($path);
            case 'rar':
                return new Rar($path);
            default:
                throw new \Exception ('unknown ext name');
        }
    }

    public static function getExtension($path)
    {
        $path = trim($path);
        $pathinfo = pathinfo($path);
        return strtolower($pathinfo['extension']);
    }
}
