<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Subcomic\Archive\ArchiveFactory;

class Comic extends Eloquent
{
    use SoftDeletingTrait;

    const FILENAME_TO_SHOW_WIDTH = 20;
    protected $table = 'comics';
    protected $dates = ['deleted_at'];

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
            return $this->getRawUrl();
        }

        return action('comicShow', ['id' => $this->id]);
    }

    /**
     * @return string|null
     */
    public function getThumbnailURL()
    {
        if ($this->isPDF()) {
            return null;
        }
        $index = CacheS::get('comic_img_idx_fst_'.$this->id);
        if ($index === 'null') {
            return null;
        }
        if ($index === null) {
            $imageList = $this->getArchive()->getImageList();
            if (empty($imageList)) {
                CacheS::set('comic_img_idx_fst_'.$this->id, 'null');
                return null;
            }
            $index = $imageList[0];
            CacheS::set('comic_img_idx_fst_'.$this->id, $index);
        }

        return action('comicImage', ['archiveFileId' => $this->id, 'index' => $index]);
    }

    /**
     * @return string
     */
    public function getRawUrl()
    {
        $separated_path = mb_split("\/", $this->path);
        foreach ($separated_path as $i => $name) {
            $encoded_path[$i] = urlencode($name);
        }
        $encoded_path = join('/', $encoded_path);
        return asset('raw/'.$encoded_path);
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
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function search($query)
    {
        $keywords = mb_split(' ', $query);
        if (count($keywords) === 0) {
            return Comic::all();
        }
        $q = Comic::where('path', 'like', '%'.array_shift($keywords).'%');
        return array_reduce($keywords, function($q, $keyword) {
            return $q->where('path', 'like', '%'.$keyword.'%');
        }, $q);
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
