<?php

class ImageFileNameDetectorTest extends TestCase
{
    public function testNormalImage()
    {
        assertTrue(ImageFileNameDetector::isImage('/image.jpg'));
        assertTrue(ImageFileNameDetector::isImage('/image.jpeg'));
        assertTrue(ImageFileNameDetector::isImage('/image.png'));
        assertTrue(ImageFileNameDetector::isImage('/image.gif'));
    }

    public function testImageInDir()
    {
        assertTrue(ImageFileNameDetector::isImage('/hoge/image.png'));
    }

    public function testImageExtCaseInsensitive()
    {
        assertTrue(ImageFileNameDetector::isImage('/image.JPG'));
    }

    public function testHiddenFile()
    {
        assertFalse(ImageFileNameDetector::isImage('/.DS_Store'));
        assertFalse(ImageFileNameDetector::isImage('/.hoge/image.jpg'));
    }

    public function testSystemFile()
    {
        assertFalse(ImageFileNameDetector::isImage('/__MACOSX/image.jpg'));
    }
}