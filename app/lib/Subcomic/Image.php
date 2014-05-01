<?php

namespace Subcomic;

class Image
{
    /** @var  \Imagick */
    protected $im;

    /**
     * @param string $blob
     */
    public function __construct($blob)
    {
        $this->im = new \Imagick;
        $this->im->readimageblob($blob, '');
    }

    public function resize()
    {
        $this->im->resizeimage(2048, 2048, \Imagick::FILTER_LANCZOS, 1, true);
    }

    /**
     * @return string
     */
    public function getBlob()
    {
        $this->im->setcompression(\Imagick::COMPRESSION_JPEG);
        $this->im->setcompressionquality(70);
        $this->im->setimageformat('jpeg');
        return $this->im->getimageblob();
    }
}