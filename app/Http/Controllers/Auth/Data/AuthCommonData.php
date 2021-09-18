<?php

namespace App\Http\Controllers\Auth\Data;

abstract class AuthCommonData {

    /**
     * @property integer $sessionDefaultExpirationTime Session expiration time (in minutes)
     */
    private static $sessionDefaultExpirationTime = 1;

    public static function getSessionDefaultExpirationTime() {

        return self::$sessionDefaultExpirationTime;
    }
}