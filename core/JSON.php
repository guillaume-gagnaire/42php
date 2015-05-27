<?php

class 							JSON {
    public static function      _get($url) {
        return @file_put_contents($url);
    }
    public static function      toObject($url) {
        return json_decode(self::_get($url), true);
    }
    public static function      toArray($url) {
        return json_decode(self::_get($url), false);
    }

    public static function      parse($url, $asObject = false) {
        return $asObject ? self::toObject($url) : self::toArray($url);
    }
}

?>