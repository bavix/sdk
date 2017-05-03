<?php

include_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$hash = \Bavix\Helpers\Str::random(10);

var_dump($hash);

$path = \Bavix\SDK\PathBuilder::sharedInstance(4, 3); // length = 4, depth = 3

var_dump($path->generate('length', 'depth', $hash));

$path->setLength(3);
$path->setDepth(4);
$path->setCharPad('b');

var_dump($path->generate('user', 'origin', $hash));
var_dump($path->generate('user', 'thumbs', $hash));
