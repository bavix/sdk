<?php

include_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$hash = \Bavix\Helpers\Str::random(10);

var_dump($hash);

$path = new \Bavix\SDK\PathBuilder();
$path->setDepth(4);
$path->setLength(3);
$path->setCharPad('b');

var_dump($path->generate('user', 'origin', $hash));
var_dump($path->generate('user', 'thumbs', $hash));
