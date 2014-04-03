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

    public function testParenthesisTag()
    {
        assertEquals(
            ['hoge', 'fuga'],
            TagDetector::detect('(hoge)(fuga)piyo.zip')
        );
    }

    public function testRecursiveTag()
    {
        assertEquals(
            ['hoge', 'fuga', 'piyo'],
            TagDetector::detect('[hoge(fuga[piyo])]pi.zip')
        );
    }
}