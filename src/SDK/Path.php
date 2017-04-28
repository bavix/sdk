<?php

namespace Bavix\SDK;

use Bavix\Helpers\Arr;
use Bavix\Helpers\Str;

class Path
{

    /**
     * @var int
     */
    protected static $limit = 2;

    /**
     * @var int
     */
    protected static $chars = 2;

    /**
     * @var string
     */
    protected static $padChar = '0';

    /**
     * @param string $data
     *
     * @return string
     */
    protected static function string($data)
    {
        return str_pad(
            $data,
            static::$limit * static::$chars,
            static::$padChar,
            STR_PAD_LEFT
        );
    }

    /**
     * @param int $data
     */
    public static function setChars($data)
    {
        static::$chars = $data;
    }

    /**
     * @param int $data
     */
    public static function setLimit($data)
    {
        static::$limit = $data;
    }

    /**
     * @param string $data
     */
    public static function setPadChar($data)
    {
        static::$padChar = $data;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public static function hash($string)
    {
        return implode('/', Arr::slice(
            Str::split(static::string($string), static::$chars),
            0, static::$limit
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
