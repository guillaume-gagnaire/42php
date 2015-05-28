<?php

class 						Http {
	public static function 	utf8() {
		header('Content-Type: text/html; charset=utf-8');
	}
	
	public static function 	throw404($force = false) {
		if (!$force) {
			global $argv;
			$route = Argv::globalRoute($argv, JSON::toArray(ROOT.'/config/routes.json'));
			if ($route) {
				$url = Argv::createUrl($route['route']['name'], $route['route']['params']);
				if (sizeof($_GET))
					$url .= '?'.http_build_query($_GET);
				Redirect::http($url);
			}
		}
		header("HTTP/1.0 404 Not Found");
        echo View::render('404');
		die();
	}

    public static function  headers()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

?>