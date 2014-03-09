<?php

use Symfony\Component\Finder\Finder;

class ArchiveFile extends Eloquent
{
    protected $table = 'archive_file';

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
            return;
        }
        return $zip;

    }

}