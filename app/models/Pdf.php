<?php

class Pdf implements ArchiveInterface
{
    /** @var Imagick */
    protected $im;

    /**
     * @param string $path
     * @throws Exception
     */
    public function __construct($path)
    {
        $this->im = new Imagick($path);
    }

    /**
     * @param int $index
     * @return string
     */
    public function getFromIndex($index)
    {
        $this->im->setiteratorindex($index);
        $this->im->setimageformat('jpg');
        return $this->im->getImageblob();
    }

    /**
     * @return array
     */
    public function getImageList()
    {
        $length = $this->im->getimagescene();
        $list = [];
        for ($i = 0; $i < $length; $i++) {
            //$stat = $this->im->setiteratorindex($i);
            $list[] = $i;
        }
        return $list;
    }
}