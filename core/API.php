<?php

/**
 * Class API
 *
 * Handles the API system
 *
 * Example:
 *
 * $api = new API();
 *
 * $api->get('me', function(){
 *      return [
 *          // content
 *      ];
 * });
 * // The same with $api->post, $api->put, $api->delete
 *
 * $api->run();
 *
 *
 * For unlocked API (without X-App-Key and X-App-Secret): new API(false);
 */
class                           API {
    public static               $specialActions = [];
    public static               $startTimestamp;
    public static               $params = [];
    public static               $__get = [];
    public static               $__post = [];
    public static               $__put = [];
    public static               $__delete = [];

    public function             __construct($locked = true) {
        self::$startTimestamp = microtime(true);
        $headers = Http::headers();

        /**
         * lang
         */
        $lang = i18n::$__defaultLanguage;
        if (isset($headers['X-Lang']) && in_array($headers['X-Lang'], i18n::$__acceptedLanguages))
            $lang = $headers['X-Lang'];
        i18n::setLang($lang);

        /**
         * Authorization of the application
         */
        if ($locked) {
            if (!isset($headers['X-App-Key']))
                new APIError(1001);
            if (!isset($headers['X-App-Secret']))
                new APIError(1002);
            $check = Db::getInstance()->Applications->findOne([
                'key' => $headers['X-App-Key'],
                'secret' => $headers['X-App-Secret']
            ]);
            if (!$check)
                new APIError(1003);
            Conf::set('api.device', $check['device']);
        } else {
            Conf::set('api.device', 'unlocked');
        }

        /**
         * Get current method
         */
        Conf::set('api.method', $_SERVER['REQUEST_METHOD']);

        /**
         * Get parameters
         */
        $json = file_get_contents('php://input');
        $params = json_decode($json, true);
        if (is_null($params))
            new APIError(1004);
        self::$params = $params;
    }

    public static function      send($data, $error = 200) {
        $ret = [
            'data' => $data,
            'elapsed' => microtime(true) - self::$startTimestamp,
            'error' => $error
        ];
        foreach (self::$specialActions as $k => $v)
            $ret[$k] => $v;
        if ($error != 200)
            $ret['errorMsg'] = APIError::getMsg($error);
        header('Content-Type: application/json');
        echo json_encode($ret, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK);
        Session::save();
        die();
    }

    public static function      createApplication($device = '') {
        $d = [
            'device' => $device,
            'key' => Text::random(20),
            'secret' => Text::random(40)
        ];
        Db::getInstance()->Applications->insert($d);
        return [
            'key' => $d['key'],
            'secret' => $d['secret']
        ];
    }

    public static function      special($k, $v) {
        self::$specialActions[$k] = $v;
    }

    /**
     * Gets the paramater value
     * @param $var The key (Ex: icon.mime)
     * @param string $default The default value
     * @return string The param
     */
    public static function 		get($var, $default = '') {
        $els = explode('.', $var);
        $current = self::$params;
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

    public function             get($path, $func) {
        self::$__get[$path] = $func;
    }

    public function             post($path, $func) {
        self::$__post[$path] = $func;
    }

    public function             put($path, $func) {
        self::$__put[$path] = $func;
    }

    public function             delete($path, $func) {
        self::$__delete[$path] = $func;
    }

    public static function      parse($query) {
        $query = urldecode($query);
        $query = explode('?', $query);
        $query = explode('/', $query[0]);
        $argv = array();
        foreach ($query as $q)
            if (strlen(trim($q)) > 0)
                $argv[] = trim($q);
        return $argv;
    }

    public static function      route($config, $argv = false) {
        uksort($config, function($a, $b) {
                if (strlen($a) == strlen($b))
                    return 0;
                return (strlen($a) > strlen($b)) ? -1 : 1;
            }
        );

        if (!$argv)
            global $argv;

        foreach ($config as $path => $value) {
            $path = self::parse($path);
            $good = true;
            $cpt = -1;
            $params = array();
            while (isset($path[++$cpt])) {
                if (!isset($argv[$cpt]) || (isset($argv[$cpt]) && $argv[$cpt] != $path[$cpt] && $path[$cpt] != '*'))
                    $good = false;
                if (isset($path[$cpt], $argv[$cpt]) && $path[$cpt] == '*')
                    $params[] = $argv[$cpt];
            }
            if ($good) {
                return [
                    'path'   => '/'.implode('/', $path),
                    'offset' => sizeof($path),
                    'selected' => $value,
                    'params' => $params
                ];
            }
        }
        return false;
    }

    public function             run($argv) {
        $var = '__'.strtolower(Conf::get('api.method'));
        $functions = self::$$var;

        $selected = self::route($functions, $argv);

        /**
         * Logging the api call
         */
        Log::create('api.call', [
            'device' => Conf::get('api.device'),
            'method' => Conf::get('api.method'),
            'path' => $argv,
            'headers' => Http::headers()
        ]);
        if ($selected) {
            $newArgv = $selected['params'];
            foreach ($argv as $i => $value) {
                if ($i >= $selected['offset']) {
                    $newArgv[] = $value;
                }
            }
            $result = call_user_func_array($selected['selected'][0], $newArgv);
            API::send($result);
        }
        new APIError(1005);
    }
}

?>