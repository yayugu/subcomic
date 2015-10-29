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
        $hintWidth = $rect->width !== 0 ? $rect->width : $rect->height;
        $hintHeight = $rect->height !==0 ? $rect->height : $rect->width;
        $this->im->setoption('jpeg:size', $hintWidth.'x'.$hintHeight); // hinting to load image faster.
        $this->im->readimageblob($blob, '');
        $useBestfit = ($rect->width !== 0 && $rect->height !==0);
        $this->im->resizeimage($rect->width, $rect->height, \Imagick::FILTER_HERMITE, 1, $useBestfit);
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