<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Helpers\JSON;

class JSONLoader implements DataInterface
{

    use DataTrait;

    /**
     * @inheritdoc
     */
    public function asArray()
    {
        if (!$this->data)
        {
            $data       = \file_get_contents($this->path);
            $this->data = JSON::decode($data);
        }

        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function save($data)
    {
        $data = $this->_fromArray($data);

        return (bool)\file_put_contents(
            $this->path,
            JSON::encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT)
        );
    }

}
