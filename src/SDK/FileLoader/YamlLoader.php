<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Slice\Slice;
use Symfony\Component\Yaml\Yaml;

class YamlLoader implements DataInterface
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
            $yml        = file_get_contents($this->path);
            $this->data = Yaml::parse($yml);
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
            $this->path,
            Yaml::dump($data)
        );
    }

}
