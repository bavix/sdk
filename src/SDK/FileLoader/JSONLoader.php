<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Helpers\JSON;
use Bavix\Slice\Slice;

class JSONLoader implements DataInterface
{

    use DataTrait;

    /**
     * @var array
     */
    protected $data;

    /**
     * @inheritdoc
     */
    public function asArray()
    {
        if (!$this->data)
        {
            $yml = file_get_contents($this->file);
            $this->data = JSON::decode($yml);
        }

        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function save($data)
    {
        if ($data instanceof Slice)
        {
            $data = $data->asArray();
        }

        return (bool)file_put_contents(
            $this->file,
            JSON::encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT)
        );
    }

}
