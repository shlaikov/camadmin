<?php

namespace Tests\Unit;

use Storage;
use Tests\TestCase;

class MinioStorageTest extends TestCase
{
    /**
     * test create new file.
     *
     * @return void
     */
    public function testCreateNewFile()
    {
        $created = Storage::cloud()->put('/test.txt', 'Hello World!');
        $this->assertTrue($created);
    }

    public function textCheckFileExists()
    {
        $exists = Storage::cloud()->exists('/test.txt');
        $this->assertTrue($exists);
    }

    public function testFileSize()
    {
        $size = Storage::cloud()->size('/test.txt');
        $this->assertEquals($size, 12);
    }

    public function testReadFile()
    {
        $text = 'Hello World!';

        $readedFile = Storage::cloud()->get('/test.txt');

        $this->assertEquals($text, $readedFile);
    }

    public function testGetFiles()
    {
        $files = Storage::cloud()->files('/');

        $this->assertCount(1, $files);
        $this->assertNotNull($files);
    }

    public function testGetUrl()
    {
        $url = Storage::cloud()->url('/test.txt');

        $this->assertNotEmpty(parse_url($url));
    }

    public function testDeleteFile()
    {
        $deleted = Storage::cloud()->delete('/test.txt');
        $this->assertTrue($deleted);

        $notExistsFile = Storage::cloud()->exists('/test.txt');
        $this->assertFalse($notExistsFile);
    }
}
