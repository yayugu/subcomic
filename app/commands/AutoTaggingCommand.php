<?php

use Illuminate\Console\Command;
use Subcomic\TagDetector;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

class AutoTaggingCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cmd:tagging_all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $comics = Comic::all();
        $tag_buffer = [];
        $tag_map_buffer = [];
        $index = 0;

        foreach ($comics as $comic) {
            $tag_names = TagDetector::detect($comic->path);
            foreach ($tag_names as $tag_name) {
                $tag_name_sha1 = sha1($tag_name, true);
                $tag_buffer[] = [
                    $tag_name,
                    $tag_name_sha1,
                ];
                $tag_map_buffer[] = [
                    $comic->id,
                    $tag_name_sha1,
                ];
                $index++;
                if (count($tag_buffer) >= 4000) {
                    $this->bulkInsertTags($tag_buffer);
                    $this->bulkInsertTagMaps($tag_map_buffer);
                    $tag_buffer = [];
                    $tag_map_buffer = [];
                    $this->info($index);
                }
            }
        }
        $this->bulkInsertTags($tag_buffer);
        $this->bulkInsertTagMaps($tag_map_buffer);
        $this->info($index);
    }

    protected function bulkInsertTags($array)
    {
        \SCUtil\BulkInsert::bulkInsertOnDuplicateKeyUpdate(
            'tags',
            ['name', 'name_sha1'],
            $array,
            true, // with_timestamps,
            '`name` = values(`name`)' // on_duplicate_key_update_query
        );
    }

    protected function bulkInsertTagMaps($array)
    {
        \SCUtil\BulkInsert::bulkInsertOnDuplicateKeyUpdate(
            'tag_maps',
            ['comic_id', 'tag_name_sha1'],
            $array,
            true, // with_timestamps,
            '`comic_id` = values(`comic_id`), `tag_name_sha1` = values(`tag_name_sha1`)' // on_duplicate_key_update_query
        );
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
