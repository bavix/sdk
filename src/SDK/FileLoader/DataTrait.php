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
     * @var array
     */
    protected $data;

    /**
     * @param array|Slice $data
     *
     * @return array
     */
    protected function _fromArray($data)
    {
        if ($data instanceof Slice)
        {
            return $data->asArray();
        }

        return $data;
    }

    /**
     * DataTrait constructor.
     *
     * @param array|Slice|string $data
     */
    public function __construct($data)
    {
        if (\is_string($data))
        {
            $this->path = $data;
            return;
        }

        $this->data = $this->_fromArray($data);
    }

    /**
     * @inheritdoc
     */
    public function asSlice($parameters = null)
    {
        return new Slice($this->asArray(), $parameters);
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
    }

}
