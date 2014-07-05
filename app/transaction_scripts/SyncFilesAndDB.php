<?php

use Subcomic\TagDetector;
use Symfony\Component\Finder\Finder;

class SyncFilesAndDB
{
    public function exec()
    {
        Log::info("Sync files and DB.");
        $this->addedComicsToDB();
        $this->removeComicsFromDB();
        $this->detectTags();
    }

    protected function addedComicsToDB()
    {
        Log::info("added start");
        $finder = Finder::create()->files()->name('/\.(?:zip|rar|pdf)\z/')->in(Config::get('subcomic.data_dir'));

        $buffer = [];
        $index = 0;

        /** @var Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $path = Normalizer::normalize($file->getRelativePathname(), Normalizer::FORM_C); // For OSXn
            $sha1 = sha1($path, true);
            $buffer[] = [
                $path,
                $sha1,
            ];
            $index++;
            if (count($buffer) >= 4000) {
                $this->bulkInsertComics($buffer);
                $buffer = [];
            }

        }
        $this->bulkInsertComics($buffer);
        Log::info($index);
        Log::info("added end");
    }

    protected function detectTags()
    {
        Log::info("detect start");
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
                }
            }
        }
        $this->bulkInsertTags($tag_buffer);
        $this->bulkInsertTagMaps($tag_map_buffer);
        Log::info($index);
        Log::info("detect end");
    }

    protected function removeComicsFromDB()
    {
        Log::info("delete start");
        $data_dir = Config::get('subcomic.data_dir');
        foreach (Comic::all() as $comic) {
            $path = $data_dir.'/'.$comic->path;
            if (!file_exists($path)) {
                $this->info("remove file which cannnot found in path:".$path);
                $comic->delete();
            }
        }
        Log::info("delete end");
    }

    protected function bulkInsertComics($array)
    {
        \SCUtil\BulkInsert::bulkInsertOnDuplicateKeyUpdate(
            'comics',
            ['path', 'path_sha1'],
            $array,
            true, // with_timestamps,
            '`path_sha1` = values(`path_sha1`)' // on_duplicate_key_update_query
        );
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
}
