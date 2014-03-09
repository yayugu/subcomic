<?php

use Symfony\Component\Finder\Finder;

class Comic extends Eloquent
{
    protected $table = 'comics';

    /**
     * @param int $id
     * @return \ZipArchive
     * @throws Exception
     */
    public static function get($id)
    {
        $finder = new Finder;
        $finder->files()->in('/Users/yayugu/comic');
        $it = $finder->getIterator();
        $it->next();
        $file = $it->current();

        $path = $file->getRealPath();

        $zip = new ZipArchive;
        if (!$zip->open($path)) {
            throw new Exception("error");
        }
        return $zip;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function pages()
    {
        $zip = new Zip('/Users/yayugu/comic/'.$this->path);
        return $zip->getImageList();
    }
}