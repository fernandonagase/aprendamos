<?php
namespace aprendamos;

spl_autoload_register(
    function ($className)
    {
        // ignore namespace prefix
        $path = str_replace(__NAMESPACE__.'\\', '', $className);
        // replace namespace separator with directory separator
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path).'.php';
        if (file_exists($path)) {
            require_once($path);
        }
    }
);