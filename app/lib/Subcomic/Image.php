<?php

namespace Subcomic;

class Image
{
    /** @var  \Imagick */
    protected $im;

    /**
     * @param string $blob
     * @param rect
     */
    public function __construct($blob, Rect $rect)
    {
        $this->im = new \Imagick;
        $this->im->setoption('jpeg:size', $rect->width.'x'.$rect->height); // hinting to load image faster.
        $this->im->readimageblob($blob, '');
        $this->im->resizeimage($rect->width, $rect->height, \Imagick::FILTER_HERMITE, 1, true);
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