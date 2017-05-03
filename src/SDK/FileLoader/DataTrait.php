<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Slice\Slice;

trait DataTrait
{

    /**
     * @var string
     */
    protected $path;

    /**
     * DataTrait constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @inheritdoc
     */
    public function asSlice($parameters = null)
    {
        return new Slice($this->asArray(), $parameters);
    }

    public function path()
    {
        return $this->path;
    }

}
