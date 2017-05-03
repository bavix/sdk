<?php

namespace Bavix\SDK;

use Bavix\Exceptions\NotFound;
use Bavix\Exceptions;
use Bavix\Helpers\Arr;
use Bavix\Helpers\File;

class FileLoader
{

    /**
     * @var array
     */
    protected static $extensions = [
        'php'  => FileLoader\PHPLoader::class,
        'yml'  => FileLoader\YamlLoader::class,
        'yaml' => FileLoader\YamlLoader::class,
        'json' => FileLoader\JSONLoader::class
    ];

    /**
     * @param string $file
     *
     * @return FileLoader\DataInterface
     *
     * @throws NotFound\Path
     * @throws Exceptions\PermissionDenied
     */
    public static function load($file)
    {
        if (!File::isFile($file))
        {
            throw new NotFound\Path($file);
        }

        if (!File::isReadable($file))
        {
            throw new Exceptions\PermissionDenied($file);
        }

        preg_match('~\.(\w+)$~', $file, $matches);

        $class = Arr::get(
            static::$extensions,
            Arr::get($matches, 1, 'php'),
            static::$extensions['php']
        );

        return new $class($file);
    }

}
