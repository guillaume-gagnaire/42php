<?php

trait                           ConfData {
    private static 				$__data = [];

    public static function 		get($var, $default = false) {
        $els = explode('.', $var);
        $current = self::$__data;
        $l = sizeof($els) - 1;
        foreach ($els as $i => $el) {
            if ($i < $l) {
                if (isset($current[$el]))
                    $current = $current[$el];
                else
                    return $default;
            } else {
                if (isset($current[$el]))
                    return $current[$el];
                return $default;
            }
        }
    }

    private static function 	recursiveSet($keys, $value, $data) {
        $insertValue = sizeof($keys) == 1;
        $key = array_shift($keys);

        if ($insertValue) {
            $data[$key] = $value;
            return $data;
        }
        $data[$key] = self::recursiveSet($keys, $value, isset($data[$key]) ? $data[$key] : []);

        return $data;
    }

    public static function 		set($k, $v) {
        $k = explode('.', $k);
        self::$__data = self::recursiveSet($k, $v, self::$__data);
    }

    private static function 	recursiveAppend($keys, $value, $data) {
        $insertValue = sizeof($keys) == 1;
        $key = array_shift($keys);

        if ($insertValue) {
            if (!isset($data[$key]))
                $data[$key] = [];
            $data[$key][] = $value;
            return $data;
        }
        $data[$key] = self::recursiveAppend($keys, $value, isset($data[$key]) ? $data[$key] : []);

        return $data;
    }

    public static function 		append($k, $v) {
        $k = explode('.', $k);
        self::$__data = self::recursiveAppend($k, $v, self::$__data);
    }

    private static function 	recursiveRemove($keys, $data) {
        $insertValue = sizeof($keys) == 1;
        $key = array_shift($keys);

        if ($insertValue) {
            unset($data[$key]);
            return $data;
        }
        $data[$key] = self::recursiveRemove($keys, $value, isset($data[$key]) ? $data[$key] : []);

        return $data;
    }

    public static function 		remove($k) {
        $k = explode('.', $k);
        self::$__data = self::recursiveRemove($k, self::$__data);
    }

    /*
    ** Use : Conf::values(function($k, $v){
            echo "$k => $v <br />";
        });
    */
    public static function 		values($callback, $prefix = 'conf', $data = null) {
        if (is_null($data))
            $data = self::$__data;

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                self::values($callback, $prefix.'.'.$k, $v);
            }
            else
                $callback($prefix.'.'.$k, $v);
        }
    }
}

?>