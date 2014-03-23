<?php

namespace Subcomic;

class TagDetector
{
    /**
     * @param string $path
     * @return array
     */
    public static function detect($path)
    {
        return array_merge(
            self::splitDirs(dirname($path)),
            self::detectBracketTags($path)
        );
    }

    /**
     * @param string $dir_path
     * @return array
     */
    public static function splitDirs($dir_path)
    {
        $parents = dirname($dir_path);
        $name = basename($dir_path);
        return $name === '.'
            ? []
            : array_merge(self::splitDirs($parents), [$name]);
    }

    /**
     * @param string $path
     * @return array
     */
    public static function detectBracketTags($path)
    {
        preg_match_all('/(?:\[)(.+?)(?:\])/', $path, $ms);
        return count($ms) >= 1
            ? $ms[1]
            : [];
    }
}