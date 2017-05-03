<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Slice\Slice;

interface DataInterface
{
    /**
     * @return Slice
     */
    public function asSlice();

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
