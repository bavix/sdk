<?php

namespace Tests;

use Bavix\Helpers\Str;
use Bavix\SDK\PathBuilder;
use Bavix\Tests\Unit;

class PathBuilderTest extends Unit
{

    /**
     * @var PathBuilder
     */
    protected $pathBuilder;

    /**
     * @var string
     */
    protected $path;

    protected function setUp()
    {
        parent::setUp();
        $this->pathBuilder = new PathBuilder();
        $this->path        = Str::random(10);
    }

    /**
     * @param int    $pos
     * @param string $path
     * @param int    $depth
     *
     * @return string
     */
    protected function sub($pos, $path = null, $depth = 2)
    {
        $path = $path ?: $this->path;
        $str  = \substr($path, 0, $pos);

        for ($i = 1; $i < $depth; ++$i)
        {
            $str .= '/' . \substr($path, $i * $pos, $pos);
        }

        return $str;
    }

    public function testHash()
    {
        $this->assertEquals(
            $this->sub(2),
            $this->pathBuilder->hash($this->path)
        );
    }

    public function testGenerate()
    {
        $this->assertEquals(
            $this->pathBuilder->generate('user', 'default', $this->path),
            'user/default/' . $this->sub(2) . '/' . $this->path
        );
    }

    public function testLength()
    {
        $this->pathBuilder->setLength(3);

        $this->assertEquals(
            $this->pathBuilder->generate('user', 'default', $this->path),
            'user/default/' . $this->sub(3) . '/' . $this->path
        );
    }

    public function testDepth()
    {
        $this->pathBuilder->setDepth(3);

        $this->assertEquals(
            $this->pathBuilder->generate('user', 'default', $this->path),
            'user/default/' . $this->sub(2, null, 3) . '/' . $this->path
        );
    }

    public function testCharPad()
    {
        $this->path = 'hello';
        $this->pathBuilder->setLength(5);
        $this->pathBuilder->setCharPad('c');

        $this->assertEquals(
            $this->pathBuilder->generate('user', 'default', $this->path),
            'user/default/' . $this->sub(5, $this->path . 'ccccc') . '/' . $this->path
        );
    }

    public function testTypeLeft()
    {
        $this->path = 'hello';

        $this->pathBuilder->setTypePad(STR_PAD_LEFT);
        $this->pathBuilder->setLength(5);
        $this->pathBuilder->setCharPad('c');

        $this->assertEquals(
            $this->pathBuilder->generate('user', 'default', $this->path),
            'user/default/' . $this->sub(5, 'ccccc' . $this->path) . '/' . $this->path
        );
    }

    public function testTypeBoth()
    {
        $this->path = 'hello';

        $this->pathBuilder->setTypePad(STR_PAD_BOTH);
        $this->pathBuilder->setLength(5);
        $this->pathBuilder->setCharPad('c');

        $this->assertEquals(
            $this->pathBuilder->generate('user', 'default', $this->path),
            'user/default/' . $this->sub(5, 'cc' . $this->path . 'ccc') . '/' . $this->path
        );
    }

}
