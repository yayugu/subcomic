<?php

class Rar implements ArchiveInterface
{
    /** @var \RarArchive */
    protected $rar;

    /**
     * @param string $path
     * @throws Exception
     */
    public function __construct($path)
    {
        $this->rar = RarArchive::open($path);
        if ($this->rar === false) {
            throw new Exception("error");
        }
    }

    /**
     * @param int $index
     * @return string
     */
    public function getFromIndex($index)
    {
        $entries = $this->rar->getEntries();
        $entry = $entries[$index];
        $stream = $entry->getStream();
        return fread($stream, $entry->getUnpackedSize());
    }

    /**
     * @return array
     */
    public function getImageList()
    {
        $entries = $this->rar->getEntries();
        $list = [];
        $index = 0;
        foreach ($entries as $entry) {
            if ($entry->isDirectory()) {
                $index++;
                continue;
            }
            $list[] = $index;
            $index++;
        }
        return $list;
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