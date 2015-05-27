<?php

class 							Session {
    public static               $apiMode = false;
    public static               $__expire = '+1 year';
    private static 				$__data = [];
    public static               $id = false;

    public static function      init() {
        /**
         * If it's cookie mode (for web)
         */
        if (!self::$apiMode && isset($_COOKIE['token'])) {
            self::$id = $_COOKIE['token'];
        }

        /**
         * If the session id is transmitted via API (via X-Token header)
         */
        if (self::$apiMode) {
            $headers = Http::headers();
            if (isset($headers['X-Token'])) {
                self::$id = $headers['X-Token'];
            }
        }

        /**
         * If a session id was transmitted, we try to get data from DB
         */
        if (self::$id !== false) {
            $d = Db::getInstance()->Sessions->findOne([
                'id' => self::$id
            ]);
            if (!$d) {
                self::$id = false;
                if (self::$apiMode) {
                    API::special('deleteToken', true);
                }
            } else
                self::$__data = $d;
        }
        self::save();

        register_shutdown_function(function(){
            Session::save();
        });
    }

    public static function      create() {
        if (self::$id === false) {
            $d = self::$__data;
            $id = Db::getInstance()->Sessions->insert($d);
            self::$id = $id;
        }
    }

    public static function      save() {
        $d = self::$__data;
        /**
         * If no active session, create a new session in DB, or update data in DB
         * If we are in API mode, no session will be created
         *
         * For the API mode, we can force the session creation with Session::create()
         */
        if (!self::$apiMode) {
            Db::getInstance()->Sessions->insert($d);
            self::$id = (string)$d['_id'];
        } elseif (self::$id !== false) {
            Db::getInstance()->Sessions->update([
                'id' => self::$id
            ], $d);
        }
        if (!self::$apiMode) {
            setcookie('token', self::$id, strtotime(self::$__expire), '/', Conf::get('cookie.domain'), false, false); // The cookie must be accessible by javascript.
        }
    }

    public static function      destroy() {
        self::$__data = [];
        if (self::$id !== false) {
            Db::getInstance()->Sessions->remove([
                'id' => self::$id
            ]);
            self::$id = null;
        }
        self::save();
    }

    public static function 		get($var, $default = '') {
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
    public static function 		values($callback, $prefix = 'session', $data = null) {
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