<?php

class 							Conf {
	private static 				$__configuration = [];
	
	public static function 		load($file) {
		if (!file_exists($file))
			return false;
	
		self::$__configuration = array_merge(self::$__configuration, json_decode(file_get_contents($file), true));
		
		return true;
	}
	
	public static function 		get($var, $default = '') {
		$els = explode('.', $var);
		$current = self::$__configuration;
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
		self::$__configuration = self::recursiveSet($k, $v, self::$__configuration);
	}
	
	/*
	** Use : Conf::values(function($k, $v){
			echo "$k => $v <br />";
		});
	*/
	public static function 		values($callback, $prefix = 'conf', $data = null) {
		if (is_null($data))
			$data = self::$__configuration;
		
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