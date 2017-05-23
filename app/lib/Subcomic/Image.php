<?php

namespace Subcomic;

class Image
{
    /** @var  \Imagick */
    protected $im;

    /** @var Rect */
    protected $rect;

    /**
     * @param rect
     */
    public function __construct(Rect $rect)
    {
        $this->im = new \Imagick;
        $this->rect = $rect;
        $hintWidth = $rect->width !== 0 ? $rect->width : $rect->height;
        $hintHeight = $rect->height !== 0 ? $rect->height : $rect->width;
        $this->im->setoption('jpeg:size', $hintWidth . 'x' . $hintHeight); // hinting to load image faster.
    }

    public function loadBlob($blob)
    {
        $this->im->readimageblob($blob, '');
        $this->resize();
    }

    public function read(string $filename)
    {
        $this->im->readImage($filename);
        $this->resize();
    }

    /**
     * @return string
     */
    public function getBlob()
    {

        return $this->im->getimageblob();
    }

    public function write(string $filename)
    {
        $this->im->writeImage($filename);
    }

    protected function resize()
    {
        $useBestfit = ($this->rect->width !== 0 && $this->rect->height !== 0);
        $this->im->resizeimage($this->rect->width, $this->rect->height, \Imagick::FILTER_HERMITE, 1, $useBestfit);
        $this->im->setcompression(\Imagick::COMPRESSION_JPEG);
        $this->im->setcompressionquality(70);
        $this->im->setimageformat('jpeg');
    }
}
