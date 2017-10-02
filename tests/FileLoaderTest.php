<?php

namespace Tests;

use Bavix\SDK\FileLoader;
use Bavix\Tests\Unit;

class FileLoaderTest extends Unit
{

    protected function getFilePath($ext)
    {
        return __DIR__ . '/etc/' . $ext . '/data.' . $ext;
    }

    public function testJson()
    {
        $this->assertArraySubset(
            require $this->getFilePath('php'),
            FileLoader::load($this->getFilePath('json'))
                ->asArray()
        );
    }

    public function testYaml()
    {
        $this->assertArraySubset(
            require $this->getFilePath('php'),
            FileLoader::load($this->getFilePath('yml'))
                ->asArray()
        );
    }

    public function testXml()
    {
        $this->assertArraySubset(
            require $this->getFilePath('php'),
            FileLoader::load($this->getFilePath('xml'))
                ->asArray()
        );
    }

    public function testPhp()
    {
        $this->assertArraySubset(
            require $this->getFilePath('php'),
            FileLoader::load($this->getFilePath('php'))
                ->asArray()
        );
    }

    /**
     * @expectedException \Bavix\Exceptions\NotFound\Path
     */
    public function testNotFoundPath()
    {
        FileLoader::load(__DIR__);
    }

    /**
     * @expectedException \Bavix\Exceptions\PermissionDenied
     */
    public function testPermissionDenied()
    {
        chmod((string)$this->tmp, 0);
        FileLoader::load($this->tmp);
    }

}
