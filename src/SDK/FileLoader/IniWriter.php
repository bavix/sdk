<?php

namespace Bavix\SDK\FileLoader;

use Bavix\Exceptions\Runtime;
use Bavix\Helpers\File;

class IniWriter
{

    /**
     * @param string $filename
     * @param array  $config
     * @param string $header
     *
     * @return int
     */
    public function toFile($filename, array $config, $header = null)
    {
        $ini = $this->toString($config, $header);
        return File::put($filename, $ini);
    }

    /**
     * @param array $config
     * @param null  $header
     *
     * @return string
     */
    public function toString(array $config, $header = null)
    {
        $ini = !empty($header) ? $header . PHP_EOL : '';

        uasort($config, function ($first, $second) {
            if (is_array($first)) {
                return 1;
            }

            if (is_array($second))
            {
                return -1;
            }

            return 0;
        });

        $names = array_keys($config);

        foreach ($names as $name)
        {
            $section = $config[$name];

            if (!is_array($section))
            {
                $ini .= $name . ' = ' . $this->encodeValue($section) . PHP_EOL;
                continue;
            }

            if (empty($section))
            {
                continue;
            }

            if (!empty($ini))
            {
                $ini .= PHP_EOL;
            }

            $ini .= "[$name]" . PHP_EOL;

            foreach ($section as $option => $value)
            {
                if (is_numeric($option))
                {
                    $option = $name;
                    $value  = (array)$value;
                }

                if (is_array($value))
                {
                    foreach ($value as $key => $currentValue)
                    {
                        $ini .= $option . '[' . $key . '] = ' . $this->encodeValue($currentValue) . PHP_EOL;
                    }
                }
                else
                {
                    $ini .= $option . ' = ' . $this->encodeValue($value) . PHP_EOL;
                }
            }

            $ini .= "\n";
        }

        return $ini;
    }

    /**
     * @param $value
     *
     * @return int|string
     */
    protected function encodeValue($value)
    {
        if (is_bool($value))
        {
            return (int)$value;
        }

        if (is_string($value))
        {
            return '"' . $value . '"';
        }

        return $value;
    }

}
