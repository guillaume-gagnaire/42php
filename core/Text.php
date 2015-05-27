<?php

class 						Text {
	public static function 	random($length = 8, $charset = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890') {
		$text = '';
		while ($length-- > 0)
			$text .= $charset[rand(0, strlen($charset) - 1)];
		return $text;
	}
	
	public static function 	beautifulDistance($distance){
		if ($distance < 1000)
			return (intval($distance / 10) * 10) . 'm';
		return str_replace('.', ',', sprintf('%.1f', $distance / 1000)).'km';
	}
	
	public static function 	slug($str, $replace = array(), $delimiter = '-') {
		setlocale(LC_ALL, 'en_US.UTF8');
		if (!empty($replace)) {
			$str = str_replace((array)$replace, ' ', $str);
		}
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
	}
}

?>