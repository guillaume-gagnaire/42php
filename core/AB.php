<?php

class 							AB {
	public static function 		render($files, $params = []) {
		return self::view('render', $files, $params);
	}
	
	public static function 		partial($files, $params = []) {
		return self::view('partial', $files, $params));
	}
	
	private static function 	view($func, $files, $params = []) {
		$key = Conf::get('page.hash');
		$page = Session::get('ab.pages.'.$key, false);
		if (!$page) {
			$page = $files[rand(0, sizeof($files) - 1)];
			Session::set('ab.pages.'.$key, $page);
		}
		return View::$func($page, $params);
	}
	
	public static function 		link($opts) {
		
	}
	
	public static function 		click($opts) {
		
	}
}

?>