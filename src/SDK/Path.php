<?php

namespace Bavix\SDK;

use Bavix\Helpers\Arr;
use Bavix\Helpers\Str;

class Path
{

    /**
     * @var int
     */
    protected static $depth = 2;

    /**
     * @var int
     */
    protected static $length = 2;

    /**
     * @var string
     */
    protected static $charPad = '0';

    /**
     * @var int
     */
    protected static $typePad = STR_PAD_RIGHT;

    /**
     * @param string $data
     *
     * @return string
     */
    protected static function string($data)
    {
        return str_pad(
            $data,
            static::$depth * static::$length,
            static::$charPad,
            static::$typePad
        );
    }

    /**
     * @param int $data
     */
    public static function setLength($data)
    {
        static::$length = $data;
    }

    /**
     * @param int $data
     */
    public static function setDepth($data)
    {
        static::$depth = $data;
    }

    /**
     * @param string $data
     */
    public static function setCharPad($data)
    {
        static::$charPad = $data;
    }

    /**
     * @param int $data
     */
    public static function setTypePad($data)
    {
        static::$typePad = $data;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public static function hash($string)
    {
        return implode('/', Arr::slice(
            Str::split(static::string($string), static::$length),
            0, static::$depth
        ));
    }

    /**
     * <type>(/<config>)/<hash>{s1}/<hash>{s2}/<hash>
     *
     * @param string $type
     * @param string $hash
     * @param string $config
     *
     * @return string
     */
    public static function generate($type, $config, $hash)
    {
        return $type . '/' . $config . '/' . static::hash($hash) . '/' . $hash;
    }
    
}
