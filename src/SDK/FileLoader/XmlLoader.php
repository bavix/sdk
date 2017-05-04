<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Slice\Slice;
use Bavix\XMLReader\XMLReader;

class XmlLoader implements DataInterface
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
            $this->data = XMLReader::sharedInstance()->asArray($this->path);
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
            XMLReader::sharedInstance()->asXML($data)
        );
    }

}
