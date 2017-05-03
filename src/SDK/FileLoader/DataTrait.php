<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Slice\Slice;

trait DataTrait
{

    /**
     * @var string
     */
    protected $file;

    /**
     * DataTrait constructor.
     *
     * @param string $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @inheritdoc
     */
    public function asSlice()
    {
        return new Slice($this->asArray());
    }

}
