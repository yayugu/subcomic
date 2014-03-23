<?php

namespace Subcomic\Archive;

interface ArchiveInterface
{
    /**
     * @param string $path
     */
    public function __construct($path);

    /**
     * @param int $index
     * @return string
     */
    public function getFromIndex($index);

    /**
     * @return array
     */
    public function getImageList();

} 