<?php

namespace SCUtil;

use Illuminate\Support\Collection;

class BulkInsert
{
    /**
     * create and execute bulk insert with on duplicate key update.
     *
     * @param string $table
     * @param array $column_names
     * @param array $values       array of array
     * @param bool $with_timestamps
     * @param string $on_duplicate_key_update_query
     */
    public static function bulkInsertOnDuplicateKeyUpdate ($table, $column_names, $values, $with_timestamps, $on_duplicate_key_update_query)
    {
        if (count($values) <= 0) {
            return;
        }
        $column_names = Collection::make($column_names);
        $values = Collection::make($values);
        if ($on_duplicate_key_update_query) {
            $column_names = self::addedTimestampsToColumns($column_names);
            $values = self::addedTimestampsToValues($values);
        }
        $query = self::createQuery($table, $column_names, $values, $on_duplicate_key_update_query);
        \DB::insert($query, $values->flatten()->toArray());
    }

    /**
     * @param Collection $column_names
     * @return Collection
     */
    protected static function addedTimestampsToColumns($column_names)
    {
        $column_names[] = 'created_at';
        $column_names[] = 'updated_at';
        return $column_names;
    }

    /**
     * @param Collection $values
     * @return Collection
     */
    protected static function addedTimestampsToValues($values)
    {
        $date_string = date('Y-m-d H:i:s');
        return $values->map(function ($record) use ($date_string) {
            $record[] = $date_string;
            $record[] = $date_string;
            return $record;
        });
    }

    /**
     * @param string $table
     * @param Collection $column_names
     * @param Collection $values       array of array
     * @param string $on_duplicate_key_update_query
     * @return string
     */
    protected static function createQuery ($table, $column_names, $values, $on_duplicate_key_update_query)
    {
        return
            'insert into '
            . $table . ' '
            . self::createColumnQuery($column_names) . ' '
            . 'values '
            . self::createPlaceholders($column_names->count(), $values->count()) . ' '
            . 'on duplicate key update '
            . $on_duplicate_key_update_query;
    }

    /**
     * @param Collection $column_names
     * @return string
     */
    protected static function createColumnQuery($column_names)
    {
        $column_name_for_query_array = $column_names->map(function($column_name) {
           return  '`' . $column_name . '`';
        })->toArray();
        return '(' . implode(', ', $column_name_for_query_array) . ')';
    }

    /**
     * @param int $count_of_columns
     * @param int $count_of_items
     * @return string
     */
    protected static function createPlaceholders($count_of_columns, $count_of_items)
    {
        $query_item = '(' . implode(',', array_fill(0, $count_of_columns, '?')) . ')';
        return implode(',', array_fill(0, $count_of_items, $query_item));
    }
}
