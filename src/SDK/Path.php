<?php

namespace Bavix\SDK;

use Bavix\Helpers\Arr;
use Bavix\Helpers\Str;

class Path
{

    /**
     * @var int
     */
    protected $depth = 2;

    /**
     * @var int
     */
    protected $length = 2;

    /**
     * @var string
     */
    protected $charPad = '0';

    /**
     * @var int
     */
    protected $typePad = STR_PAD_RIGHT;

    /**
     * @param string $data
     *
     * @return string
     */
    protected function string($data)
    {
        return str_pad(
            $data,
            $this->depth * $this->length,
            $this->charPad,
            $this->typePad
        );
    }

    /**
     * @param int $data
     */
    public function setLength($data)
    {
        $this->length = $data;
    }

    /**
     * @param int $data
     */
    public function setDepth($data)
    {
        $this->depth = $data;
    }

    /**
     * @param string $data
     */
    public function setCharPad($data)
    {
        $this->charPad = $data;
    }

    /**
     * @param int $data
     */
    public function setTypePad($data)
    {
        $this->typePad = $data;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public function hash($string)
    {
        return implode('/', Arr::slice(
            Str::split($this->string($string), $this->length),
            0, $this->depth
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
    public function generate($type, $config, $hash)
    {
        return $type . '/' . $config . '/' . $this->hash($hash) . '/' . $hash;
    }
    
}
