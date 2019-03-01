<?php

namespace UniGen\Test\FileSystem;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use UniGen\FileSystem\Exception\FileSystemException;
use UniGen\FileSystem\NativeFileSystem;

class NativeFileSystemTest extends TestCase
{
    /** @var NativeFileSystem */
    private $sut;

    /** @var vfsStreamDirectory */
    private $fileSystem;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fileSystem = vfsStream::setup('dir', 755, [
            'validFile' => 'content'
        ]);

        $this->sut = new NativeFileSystem();
    }

    public function testExistShouldReturnTrueWhenFileExist()
    {
        $this->assertTrue($this->sut->exist(vfsStream::url('dir/validFile')));
    }

    public function testExistShouldReturnFalseWhenFileDoesNotExist()
    {
        $this->assertFalse($this->sut->exist(vfsStream::url('dir/notExistingFile')));
    }

    public function testReadShouldReturnFileContent()
    {
        $this->assertSame('content', $this->sut->read(vfsStream::url('dir/validFile')));
    }

    public function testReadShouldReturnThrowExceptionWhenReadFailed()
    {
        $this->expectException(FileSystemException::class);
        $this->expectExceptionMessage('Error occurred during file read process');

        @$this->sut->read(vfsStream::url('dir/invalidFile'));
    }

    public function testWriteShouldWriteAFile()
    {
        $this->sut->write(vfsStream::url('dir/secondDir/path'), 'secondContent');

        $this->assertEquals('secondContent', $this->sut->read(vfsStream::url('dir/secondDir/path')));
    }
}
