<?php

use Subcomic\Archive\ArchiveFactory;

class Comic extends Eloquent
{
    protected $table = 'comics';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tags()
    {
        return $this->belongsToMany('Tag', 'tag_maps');
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
    public function fileName()
    {
        return basename($this->path);
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