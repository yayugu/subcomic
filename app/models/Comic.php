<?php

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
        return '/Users/yayugu/comic/'.$this->path;
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
}