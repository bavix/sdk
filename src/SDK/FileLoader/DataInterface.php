<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Slice\Slice;

interface DataInterface
{
    /**
     * @param array|Slice $parameters
     *
     * @return Slice
     */
    public function asSlice($parameters = null);

    /**
     * @return array
     */
    public function asArray();

    /**
     * @param array|Slice $data
     *
     * @return bool
     */
    public function save($data);
}
