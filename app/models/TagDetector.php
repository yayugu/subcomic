<?php

class TagDetector
{
    /**
     * @param string $path
     * @return array
     */
    public static function detect($path)
    {
        return self::splitDirs(dirname($path));
    }

    /**
     * @param string $dir_path
     * @return array
     */
    public static function splitDirs($dir_path)
    {
        $parents = dirname($dir_path);
        $name = basename($dir_path);
        return $parents === '.'
            ? [$name]
            : array_merge(self::splitDirs($parents), [$name]);
    }
}