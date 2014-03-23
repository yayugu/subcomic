<?php

use Subcomic\TagDetector;

class TagDetectorTest extends TestCase
{
    public function testDirectoryTag()
    {
        assertEquals(
            ['hoge', 'fuga'],
            TagDetector::detect('hoge/fuga/piyo.zip')
        );

        assertEquals(
            [],
            TagDetector::detect('piyo.zip')
        );
    }

    public function testBracketTag()
    {
        assertEquals(
            ['hoge', 'fuga'],
            TagDetector::detect('[hoge][fuga]piyo.zip')
        );
    }
}