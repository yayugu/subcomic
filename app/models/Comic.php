<?php

use Symfony\Component\Finder\Finder;

class Comic extends Eloquent
{
    protected $table = 'comics';

    /**
     * @return ArchiveInterface
     * @throws Exception
     */
    public function getArchive()
    {
        $path = '/Users/yayugu/comic/'.$this->path;
        return ArchiveFactory::create($path);
    }
}