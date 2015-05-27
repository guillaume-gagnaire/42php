<?php

class                               Db {
    private static                  $__instance = null;

    public static function          getInstance() {
        if (is_null(self::$__instance)) {
            try {
                $pdo = new PDO(Conf::get('pdo.dsn'), Conf::get('pdo.user'), Conf::get('pdo.pass'));
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
            }
            self::$__instance = new DbHandle($pdo, Conf::get('pdo.prefix'));
        }
        return self::$__instance;
    }

    public static function          get($query) {
        $pdo = Db::getInstance()->pdo();

    }

    public static function          query($query) {
        $pdo = Db::getInstance()->pdo();

    }

    public static function          exec($query) {
        $pdo = Db::getInstance()->pdo();

    }

    public static function          where($query = []) {

    }

    public static function          order($query = []) {

    }
}

?>