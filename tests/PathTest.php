<?php

namespace Tests;

use Bavix\SDK\Path;
use Bavix\Tests\Unit;

class PathTest extends Unit
{

    public function testSlash()
    {
        $this->assertEquals(
            __DIR__ . '/',
            Path::slash(__DIR__)
        );

        $this->assertEquals(
            __DIR__ . '/',
            Path::slash(__DIR__ . '/')
        );

        $this->assertEquals(
            __DIR__ . '/',
            Path::slash(__DIR__ . '/////')
        );
    }

}
