<?php
namespace picp\upload;

class UploaderBox
{
    protected static $uploader_map;

    static function set($key, $uploader)
    {
        self::$uploader_map[$key] = $uploader;
    }

    static function get($key)
    {
        return self::$uploader_map[$key];
    }

    static function unset($key)
    {
        if (array_key_exists($key, self::$uploader_map)) {
            unset(self::$uploader_map[$key]);
        }
    }
}

UploaderBox::set('local', new local());
UploaderBox::set('smms', new smms());
