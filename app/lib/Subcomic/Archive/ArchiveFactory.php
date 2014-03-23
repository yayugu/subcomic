<?php

namespace Subcomic\Archive;

class ArchiveFactory
{
    /**
     * @param string $path
     * @throws Exception
     * @return \ArchiveInterface
     */
    public static function create($path)
    {
        $path = trim($path);
        $pathinfo = pathinfo($path);
        $ext = strtolower($pathinfo['extension']);
        switch ($ext) {
            case 'zip':
                return new Zip($path);
            case 'rar':
                return new Rar($path);
            default:
                throw new Exception ('unknown ext name');
        }
    }
}