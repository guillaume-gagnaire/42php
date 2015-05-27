<?php

class				Redirect {
	public static function	permanent($to, $force = false) {
		header('HTTP/1.1 301 Moved Permanently', false, 301);
		Redirect::http($to, $force);
	}
	
	public static function	http($to, $force = false) {
		Session::save();
		header("Location: $to");
		die();
	}
}

?>