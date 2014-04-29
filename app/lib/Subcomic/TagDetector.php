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
        $ms = self::bracketMatch($path);
        if (empty($ms[1])) {
            return [];
        }
        $tag_removed_path = str_replace($ms[0], '', $path);
        $tags_inner_tags = self::detect($tag_removed_path);
        return array_merge($tags_inner_tags, $ms[1]);
    }

    /**
     * @param string $path
     * @return array
     */
    protected static function bracketMatch($path)
    {
        $begin_tags = '\[ \( （ 【 ［';
        $end_tags   = '\] \) ） 】 ］';
        $pattern = '/
          [ '.$begin_tags.' ]
          ([^ '.$begin_tags.' '.$end_tags.' ]+)
          [ '.$end_tags.' ]
        /xu';
        preg_match_all($pattern, $path, $matches);
        return $matches;
    }
}