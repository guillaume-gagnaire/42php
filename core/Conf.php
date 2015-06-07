<?php

class 							Conf {
    use ConfData;

	public static function 		load($file) {
		if (!file_exists($file))
			return false;
	
		self::$__data = array_merge(self::$__data, json_decode(file_get_contents($file), true));
		
		return true;
	}
}

?>