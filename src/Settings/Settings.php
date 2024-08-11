<?php

namespace Lib\Settings;

class Settings
{
    private $settings = [];

    public function __construct(array $files)
    {
        $pathConfig = BASE_DIR . 'app/Config/';

        foreach ($files as $alias => $file) {
            if (file_exists($pathConfig . $file)) {
                $this->settings[$alias] = require $pathConfig . $file;
            }
        }
    }

    public function getAlias(string $alias, $default = [])
    {
        return $this->settings[$alias] ?? $default;
    }
}

# end of file
