<?php

use Subcomic\TagDetector;
use Symfony\Component\Finder\Finder;

class SyncFilesAndDB
{
    const COMPRESS_METHOD_ERROR = 100;

    public function exec()
    {
        Log::info("Sync files and DB.");
        $this->addedComicsToDB();
        $this->removeComicsFromDB();
        $this->detectTags();
        $this->addedCompressLevel();
    }

    protected function addedComicsToDB()
    {
        Log::info("added start");
        $finder = Finder::create()->files()->name('/\.(?:zip|rar|pdf)\z/')->in(Config::get('subcomic.data_dir'));

        $buffer = [];
        $index = 0;

        /** @var Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $path = Normalizer::normalize($file->getRelativePathname(), Normalizer::FORM_C); // For OSX
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

    protected function addedCompressLevel()
    {
        Log::info("compress method add start");
        $comics = Comic::all();
        $buffer = [];
        foreach ($comics as $comic) {
            if ($comic->getExtension() !== 'zip') {
                continue;
            }
            /** @var \Subcomic\Archive\Zip $archive */
            $archive = $comic->getArchive();
            $stat = $archive->getStat();
            if ($stat !== null) {
                $compress = $stat['comp_method'];
                $one_image_size_byte = $stat['size'];
            } else {
                $compress = self::COMPRESS_METHOD_ERROR;
                $one_image_size_byte = 0;
            }
            $buffer[] = [
                'id' => $comic->id,
                'compress' => $compress,
                'one_image_size' => $one_image_size_byte,
            ];
            if (count($buffer) >= 4000) {
                $this->bulkInsertStat($buffer);
                $buffer = [];
            }
        }
        $this->bulkInsertStat($buffer);
        Log::info("compress method add end");
    }

    protected function removeComicsFromDB()
    {
        Log::info("delete start");
        $data_dir = Config::get('subcomic.data_dir');
        foreach (Comic::all() as $comic) {
            $path = $data_dir . '/' . $comic->path;
            if (!file_exists($path)) {
                Log::info("remove file which cannnot found in path:" . $path);
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
            '`path_sha1` = values(`path_sha1`), `deleted_at` = NULL' // on_duplicate_key_update_query
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

    protected function bulkInsertStat($array)
    {
        \SCUtil\BulkInsert::bulkInsertOnDuplicateKeyUpdate(
            'comics',
            ['id', 'compress', 'one_image_size'],
            $array,
            false, // with_timestamps,
            '`id` = values(`id`), `compress` = values(`compress`), `one_image_size` = values(`one_image_size`)' // on_duplicate_key_update_query
        );
    }
}
