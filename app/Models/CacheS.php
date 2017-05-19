<?php

class CacheS
{
    /**
     * @param string $key
     * @return string|null
     */
    public static function get($key) {
        $result = DB::table('cache')->where('key', $key)->first();
        if (!$result) {
            return $result;
        }
        return $result->value;
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function set($key, $value) {
        return DB::statement(
            'insert into `cache` (`key`, `value`) values (?, ?) on duplicate key update value = ?',
            [$key, $value, $value]
        );
    }
}