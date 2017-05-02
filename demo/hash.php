<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$hash = \Bavix\Helpers\Str::random(10);

var_dump($hash);

\Bavix\SDK\Path::setDepth(4);
\Bavix\SDK\Path::setLength(3);
\Bavix\SDK\Path::setCharPad('b');

var_dump(\Bavix\SDK\Path::generate('user', 'origin', $hash));
var_dump(\Bavix\SDK\Path::generate('user', 'thumbs', $hash));
