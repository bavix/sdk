<?php

namespace Bavix\SDK\FileLoader;

class PHPLoader implements DataInterface
{

    use DataTrait;

    /**
     * @inheritdoc
     */
    public function asArray()
    {
        if (!$this->data)
        {
            $this->data = require $this->path;
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
            '<?php return ' . var_export($data, true) . ';'
        );
    }

}
