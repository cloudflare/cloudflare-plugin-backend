<?php

namespace CF\Integration;

class DefaultConfig implements ConfigInterface
{
    /** @var array|null */
    private $config;

    /**
     * @param string $config from file_get_contents()
     */
    public function __construct($config)
    {
        $this->config = json_decode($config, true);
    }

    /**
     * @param $key
     *
     * @return mixed value or key or null
     */
    public function getValue($key)
    {
        if ($this->config && array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        return null;
    }
}
