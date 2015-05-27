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

    public static function          quote($str) {
        return Db::getInstance()->pdo()->quote($str);
    }

    public static function          get($query) {
        $req = Db::getInstance()->pdo()->query($query);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public static function          query($query) {
        $req = Db::getInstance()->pdo()->query($query);
        return $req->fetchAll();
    }

    public static function          exec($query) {
        return Db::getInstance()->pdo()->exec($query);
    }

    public static function          where($query = [], $join = ' AND ', $parent = '') {
        $str = [];

        foreach ($query as $k => $v) {
            if (is_array($v) && !in_array($k, ['$in', '$nin']))
                $str[] = '('.Db::where($v, $join, $k).')';
            else
                switch ($k) {
                    case '$like':
                        $str[] = '`'.$k.'` LIKE '.Db::quote($v);
                        break;
                    case '$eq':
                        $str[] = '`'.$k.'`='.Db::quote($v);
                        break;
                    case '$gt':
                        $str[] = '`'.$k.'`>'.Db::quote($v);
                        break;
                    case '$gte':
                        $str[] = '`'.$k.'`>='.Db::quote($v);
                        break;
                    case '$lt':
                        $str[] = '`'.$k.'`<'.Db::quote($v);
                        break;
                    case '$lte':
                        $str[] = '`'.$k.'`<='.Db::quote($v);
                        break;
                    case '$ne':
                        $str[] = '`'.$k.'`!='.Db::quote($v);
                        break;
                    case '$in':
                        $els = [];
                        foreach ($v as $vv)
                            $els[] = Db::quote($vv);
                        $str[] = '`'.$parent.'` IN ('.implode(', ', $els).')';
                        break;
                    case '$nin':
                        $els = [];
                        foreach ($v as $vv)
                            $els[] = Db::quote($vv);
                        $str[] = '`'.$parent.'` NOT IN ('.implode(', ', $els).')';
                        break;
                    case '$or':
                        $str[] = '('.Db::where($v, ' OR ').')';
                        break;
                    case '$and':
                        $str[] = '('.Db::where($v, ' AND ').')';
                        break;
                    case '$not':
                        $str[] = '!('.Db::where($v, ' OR ').')';
                        break;
                    default:
                        $str[] = '`'.$k.'`='.Db::quote($v);
                        break;
                }
        }

        return implode($join, $str);
    }

    public static function          order($query = []) {
        $str = [];
        foreach ($query as $k => $v) {
            $str[] = '`'.$k.'` '.($v > 0 ? 'ASC' : 'DESC');
        }
        return implode(', ', $str);
    }
}

?>