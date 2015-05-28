<?php

class 							Argv {
	public static function 		parse($url, $offset = 0) {
		$argv = array();
		$url = explode('?', $url);
		$url = explode('/', $url[0]);
		foreach ($url as $u)
			if (strlen(trim($u)))
				$argv[] = trim($u);
		while ($offset--)
			array_shift($argv);
		return $argv;
	}
	
	private static function 	routeMatch($url, $route) {
		$tmp = array();
		preg_match_all('/(\{[a-z0-9\-\_]+\})/i', $route, $matches);
		foreach ($matches[0] as $k)
			$tmp[] = [substr($k, 1, strlen($k) - 2), ''];
		
		$originalRoute = $route;
		
		if (strstr($originalRoute, '*') !== false) {
			if (fnmatch($originalRoute, $url)) 
				return ['match' => true, 'params' => [], 'offset' => 0 ];
			return ['match' => false, 'params' => [], 'offset' => 0 ];
		}
		
		$route = preg_replace('/(\{[a-z0-9\-\_]+\}\?)/i', '([\w\.\-\_]*)', $route);
		$route = preg_replace('/(\{[a-z0-9\-\_]+\})/i', '([\w\.\-\_]+)', $route);
		$route = str_replace('/', '\/', $route);
		$route = '/^'.$route.'?$/i';
		$res = preg_match_all($route, $url, $matches);
		for ($i = 1; $i < sizeof($matches); $i++)
			if (isset($matches[$i][0]))
				$tmp[$i - 1][1] = $matches[$i][0];
		
		$params = array();
		foreach ($tmp as $t) 
			$params[$t[0]] = $t[1];
			
		return ['match' => $res ? true : false, 'params' => $params, 'offset' => substr($originalRoute, -2) == '?/' ? 1 : 0 ];
	}
	
	public static function 		route($argv, $routes, $fieldToReturn = 'controller') {
		if (!sizeof($argv))
			$url = '/';
		else
			$url = '/'.implode('/', $argv).'/';
		$offset = -1;
		$toReturn = false;
		$lang = Conf::get('oldlang') != '' ? Conf::get('oldlang') : Conf::get('lang');

		foreach ($routes as $name => $r) {
			if (!isset($r['routes'][$lang]))
				continue;
			$route = $r['routes'][$lang];
			if (substr($route, -1) != '/')
				$route .= '/';
			$res = self::routeMatch($url, $route);
			if ($res['match']) {
				$potentialOffset = sizeof(self::parse($route)) - $res['offset'];
				if ($potentialOffset > $offset) {
					$offset = $potentialOffset;
					$toReturn = [
						$fieldToReturn => $r[$fieldToReturn],
						'params' => $res['params'],
						'route' => array(
							'params' => $res['params'],
							'name' => $name
						),
						'offset' => $offset
					];
				}
			}
		}
		return $toReturn;
	}
	
	public static function 		globalRoute($argv, $routes, $fieldToReturn = 'controller') {
		if (!sizeof($argv))
			$url = '/';
		else
			$url = '/'.implode('/', $argv).'/';
		$offset = -1;
		$toReturn = false;
		foreach ($routes as $name => $r) {
			foreach ($r['routes'] as $lang => $route) {
				if (substr($route, -1) != '/')
					$route .= '/';
				$res = self::routeMatch($url, $route);
				if ($res['match']) {
					$potentialOffset = sizeof(self::parse($route)) - $res['offset'];
					if ($potentialOffset > $offset) {
						$offset = $potentialOffset;
						$toReturn = [
							$fieldToReturn => $r[$fieldToReturn],
							'params' => $res['params'],
							'route' => array(
								'params' => $res['params'],
								'name' => $name
							),
							'offset' => $offset,
							'lang' => $lang
						];
					}
				}
			}
		}
		return $toReturn;
	}
	
	public static function 		createUrl($name, $params = [], $lang = false, $site = false) {
		if (!$site)
			$site = Conf::get('site');
		if (!$lang)
			$lang = Conf::get('lang');
		$routes = json_decode(file_get_contents(ROOT.'/config/routes.'.$site.'.json'), true);
		if (!isset($routes[$name]['routes'][$lang]))
			return '/';
		$url = str_replace('?', '', $routes[$name]['routes'][$lang]);
		foreach ($params as $k => $v)
			$url = str_replace('{'.$k.'}', $v, $url);
		return $url;
	}
}

?>