<?php

namespace Bavix\SDK;

class Path
{

    /**
     * @param string $data
     *
     * @return string
     */
    public static function slash($data)
    {
        return \rtrim($data, '\\/') . DIRECTORY_SEPARATOR;
    }

}
