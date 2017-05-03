<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Slice\Slice;

class PHPLoader implements DataInterface
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
            $this->data = require $this->file;
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
            '<?php return ' . var_export($data, true) . ';'
        );
    }

}
