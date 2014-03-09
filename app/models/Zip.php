<?php

class Zip
{
    /** @var \ZipArchive */
    protected $zip;

    /**
     * @param string $path
     * @throws Exception
     */
    public function __construct($path)
    {
        $this->zip = new ZipArchive;
        if (!$this->zip->open($path)) {
            throw new Exception("error");
        }
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
                || $this->statIsSystemFile($stat)
            ) {
                continue;
            }
            $list[] = $i;
        }
        return $list;
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
     * @param array $stat
     * @return bool
     */
    protected function statIsSystemFile($stat)
    {
        return preg_match('/\A__MACOSX\//', $stat['name']);
    }
}