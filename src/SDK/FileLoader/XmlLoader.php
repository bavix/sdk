<?php

namespace Bavix\SDK\FileLoader;

use Bavix\XMLReader\XMLReader;

class XmlLoader implements DataInterface
{

    use DataTrait;

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
        $data = $this->_fromArray($data);

        return (bool)\file_put_contents(
            $this->path,
            XMLReader::sharedInstance()->asXML($data)
        );
    }

}
