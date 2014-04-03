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
        $ms = self::bracketMatch($path);
        if (empty($ms[1])) {
            return [];
        }
        $tag_removed_path = str_replace($ms[0], '', $path);
        $tags_inner_tags = self::detectBracketTags($tag_removed_path);
        return array_merge($tags_inner_tags, $ms[1]);
    }

    /**
     * @param string $path
     * @return array
     */
    protected static function bracketMatch($path)
    {
        $begin = '[ \[ \( ]';
        $end   = '[ \] \) ]';
        $tag_body = '[^ \[ \] \( \) ]+';
        $pattern = '/
          '.$begin.'
          ('.$tag_body.')
          '.$end.'
        /x';
        preg_match_all($pattern, $path, $matches);
        return $matches;
    }
}