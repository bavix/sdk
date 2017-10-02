<?php

namespace Bavix\SDK;

use Bavix\Exceptions\NotFound;
use Bavix\Exceptions;
use Bavix\Helpers\Arr;
use Bavix\Helpers\File;
use Bavix\Helpers\PregMatch;

class FileLoader
{

    /**
     * @var array
     */
    protected static $extensions = [
        'php'  => FileLoader\PHPLoader::class,
        'yml'  => FileLoader\YamlLoader::class,
        'yaml' => FileLoader\YamlLoader::class,
        'json' => FileLoader\JSONLoader::class,
        'xml'  => FileLoader\XmlLoader::class,
    ];

    /**
     * @return array
     */
    public static function extensions()
    {
        return Arr::getKeys(static::$extensions);
    }

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

        $preg = PregMatch::first('~\.(?<ext>\w+)$~', $file);

        $class = Arr::get(
            static::$extensions,
            Arr::get($preg->matches, 'ext', 'php'),
            static::$extensions['php']
        );

        return new $class($file);
    }

}
