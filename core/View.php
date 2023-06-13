<?php

namespace Core;

class View
{
    static public function render(string $view, array $args = []) : void
    {
        $file = VIEW_DIR . $view . '.php';

        if (is_readable($file)) {
            extract($args, EXTR_SKIP );
            require $file;
        } else {
            throw new \Exception("[{$file}] not found or not readable", 404);
        }
    }
}
