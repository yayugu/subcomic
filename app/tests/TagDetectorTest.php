<?php

class TagDetectorTest extends TestCase
{
    public function testDirectoryTag()
    {
        assertEquals(
            ['hoge', 'fuga'],
            TagDetector::detect('hoge/fuga/piyo.zip')
        );
    }
}