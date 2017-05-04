<?php

include_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$dataLoader = \Bavix\SDK\FileLoader::load(__DIR__ . '/data.php');

$slice          = $dataLoader->asSlice();
$slice['hello'] = random_int(PHP_INT_MIN, PHP_INT_MAX);

var_dump($dataLoader->save($slice));

$yml = new \Bavix\SDK\FileLoader\YamlLoader(__DIR__ . '/data.yml');
$yml->save($slice);

$json = new \Bavix\SDK\FileLoader\JSONLoader(__DIR__ . '/data.json');
$json->save($slice);

$xml = new \Bavix\SDK\FileLoader\XmlLoader(__DIR__ . '/data.xml');
$xml->save($slice);
