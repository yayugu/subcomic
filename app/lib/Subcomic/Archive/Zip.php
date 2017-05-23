<?php

namespace Subcomic\Archive;

use Subcomic\ImageFileNameDetector;

class Zip implements ArchiveInterface
{
    /** @var \ZipArchive */
    protected $zip;

    /**
     * @param string $path
     * @throws \Exception
     */
    public function __construct($path)
    {
        $this->zip = new \ZipArchive;
        if (!$this->zip->open($path)) {
            throw new \Exception("error");
        }
    }

    /**
     * @param int $index
     * @return string
     */
    public function getFromIndex($index)
    {
        return $this->zip->getFromIndex($index);
    }

    /**
     * @return array
     */
    public function getImageList()
    {
        $list = [];
        for ($i = 0; $i < $this->zip->numFiles; $i++) {
            $stat = $this->zip->statIndex($i);
            if ($this->statIsDir($stat)
                || !ImageFileNameDetector::isImage($stat['name'])
            ) {
                continue;
            }
            $list[$i] = $stat['name'];
        }
        natcasesort($list);
        return array_keys($list);
    }

    /**
     * @param array $stat
     * @return bool
     */
    protected function statIsDir($stat)
    {
        return ($stat['size'] === 0 && preg_match('/\/\z/', $stat['name']));
    }

    /**
     * @return array|null
     */
    public function getStat()
    {
        $imageList = $this->getImageList();
        if (empty($imageList)) {
            return null;
        }
        return $this->zip->statIndex($imageList[0]);
    }
}
