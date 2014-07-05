<?php

use Subcomic\Archive\ArchiveFactory;

class Comic extends Eloquent
{
    const FILENAME_TO_SHOW_WIDTH = 20;
    protected $table = 'comics';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tags()
    {
        return $this->belongsToMany('Tag', 'tag_maps', 'comic_id', 'tag_name_sha1');
    }

    /**
     * @return ArchiveInterface
     * @throws Exception
     */
    public function getArchive()
    {
        return ArchiveFactory::create($this->getAbsolutePath());
    }

    /**
     * @return string
     */
    public function getAbsolutePath()
    {
        return Config::get('subcomic.data_dir').'/'.$this->path;
    }

    /**
     * @return string
     */
    public function getFileNameToShow()
    {
        $filename = basename($this->path);
        if (mb_strwidth($filename) >= self::FILENAME_TO_SHOW_WIDTH) {
            return $filename;
        }
        $parent_dirname = basename(dirname($this->path));
        if ($parent_dirname === '') {
            return $filename;
        }
        return $parent_dirname . '/' . $filename;
    }

    public function getUrlToShow()
    {
        if ($this->isPDF()) {
            return asset('raw/'.$this->path);
        }

        return action('comicShow', ['id' => $this->id]);
    }

    /**
     * @return bool
     */
    public function isPDF()
    {
        $path_info = pathinfo($this->path);
        $ext = strtolower($path_info['extension']);
        return $ext === 'pdf';
    }

    /**
     * @param $tag_name
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function findByTagName($tag_name)
    {
        $tag = Tag::where('name', '=', $tag_name)->first();
        return $tag->comics()->get();
    }
}
