<?php

class 							Session {
    use ConfData;

    public static               $apiMode = false;
    public static               $__expire = '+1 year';
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
		 * Clean old sessions
		 */
		Db::getInstance()->Sessions->remove([
			'expire' => [
				'$lt' => time()
			]
		]);
		
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
                self::$__data = json_decode($d['data'], true);
        }
        self::save();

        register_shutdown_function(function(){
            Session::save();
        });
    }

    public static function      create() {
        if (self::$id === false) {
	        $d = ['data' => json_encode(self::$__data), 'expire' => strtotime(self::$__expire)];
            self::$id = Db::getInstance()->Sessions->insert($d);
        }
    }

    public static function      save() {
        $d = ['data' => json_encode(self::$__data), 'expire' => strtotime(self::$__expire)];
        /**
         * If no active session, create a new session in DB, or update data in DB
         * If we are in API mode, no session will be created
         *
         * For the API mode, we can force the session creation with Session::create()
         */
        if (!self::$apiMode && self::$id === false) {
            self::$id = Db::getInstance()->Sessions->insert($d);
        } elseif (self::$id !== false) {
            Db::getInstance()->Sessions->update([
                'id' => self::$id
            ], $d);
        }
        if (!self::$apiMode) {
            if (!headers_sent())
                setcookie('token', (string)self::$id, strtotime(self::$__expire), '/', Conf::get('cookie.domain'), false, false); // The cookie must be accessible by javascript.
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
}

?>