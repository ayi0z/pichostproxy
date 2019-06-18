<?php

class loader
{
    public static $vendorMap = array(
        'picp' => __DIR__ . DIRECTORY_SEPARATOR
    );

    public static function autoload($class)
    {
        $file = self::findFile($class);
        if (file_exists($file)) {
            self::requireFile($file);
        }
    }

    private static function findFile($class)
    {
        $vender = substr($class, 0, strpos($class, '\\'));
        $venderDir = self::$vendorMap[$vender];
        $filePath = substr($class, strlen($vender)) . '.php';
        return strtr($venderDir . $filePath, '\\', DIRECTORY_SEPARATOR);
    }

    private static function requireFile($file)
    {
        if (is_file($file)) {
            require $file;
        }
    }
}

spl_autoload_register('loader::autoload');