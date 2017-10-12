<?php

namespace Bavix\SDK\FileLoader;

class IniLoader implements DataInterface
{

    use DataTrait;

    /**
     * @var IniWriter
     */
    protected $writer;

    /**
     * @return IniWriter
     */
    protected function writer()
    {
        if (!$this->writer)
        {
            $this->writer = new IniWriter();
        }

        return $this->writer;
    }

    /**
     * @inheritdoc
     */
    public function asArray()
    {
        if (!$this->data)
        {
            $this->data = \parse_ini_file($this->path, true);
        }

        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function save($data)
    {
        $data = $this->_fromArray($data);

        return (int)$this->writer()
            ->toFile($this->path, $data);
    }

}
