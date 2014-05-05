<?php

namespace Subcomic;

class Image
{
    /** @var  \Imagick */
    protected $im;

    /**
     * @param string $blob
     * @param int width
     * @param int height
     */
    public function __construct($blob, $width, $height)
    {
        $this->im = new \Imagick;
        $this->im->setoption('jpeg:size', $width.'x'.$height); // hinting to load image faster.
        $this->im->readimageblob($blob, '');
        $this->im->resizeimage($width, $height, \Imagick::FILTER_LANCZOS, 1, true);
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