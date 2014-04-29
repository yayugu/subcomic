<?php

use Subcomic\TagDetector;

class TagDetectorTest extends TestCase
{
    public function testBracketTag()
    {
        assertEquals(
            ['hoge', 'fuga'],
            TagDetector::detect('[hoge][fuga]piyo.zip')
        );
    }

    public function testVariousTag()
    {
        assertEquals(
            ['hoge', 'fuga'],
            TagDetector::detect('(hoge)（fuga）piyo.zip')
        );

        assertEquals(
            ['hoge', 'fuga'],
            TagDetector::detect('【hoge】［fuga］piyo.zip')
        );
    }

    public function testRecursiveTag()
    {
        assertEquals(
            ['hoge', 'fuga', 'piyo'],
            TagDetector::detect('[hoge(fuga[piyo])]pi.zip')
        );
    }

    public function testDirectoryTag()
    {
        assertEquals(
            [],
            TagDetector::detect('hoge/fuga/piyo.zip')
        );

        assertEquals(
            ['hoge', 'fuga', 'piyo'],
            TagDetector::detect('[hoge]/[fuga]/[piyo]pipi.zip')
        );
    }
}