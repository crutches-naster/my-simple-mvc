<?php

namespace Config;

class Config
{
    protected array $configs = [];
    protected static Config|null $instance = null;

    // Config::get('db.user') -> string/null
    public static function get(string $name): string|null
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance->getParam($name);
    }

    public function getParam(string $name): string|null
    {
        return $this->findParamByKeys( explode('.', $name), $this->readConfigs() );
    }

    protected function readConfigs(): array
    {
        if ( empty($this->configs) ) {
            $this->configs = include CONFIG_DIR . '/configuration.php';
        }

        return $this->configs;
    }

    protected function findParamByKeys(array $keys = [], array $configs = []): string|null
    {
        $value = null;

        if (empty($keys)) {
            return $value;
        }

        $needle = array_shift($keys);

        if (array_key_exists($needle, $configs)) {
            $value = is_array( $configs[$needle] )
                ? $this->findParamByKeys($keys, $configs[$needle] )
                : $configs[$needle];
        }

        return $value;
    }
}
