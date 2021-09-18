<?php

namespace App\Helpers\File;

abstract class FileHelper {

    public static function exists(string $fileName) {

        return self::_exists($fileName);
    }

    // Private methods

    private static function _exists(string $fileName) {

        return file_exists($fileName);
    }
}