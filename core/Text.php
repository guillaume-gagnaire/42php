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
		$str = self::ru2lat($str);
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
	
		return $clean;
	}
	
	public static function 	ru2lat($str) {
	    $tr = array(
		    "А"=>"a", "Б"=>"b", "В"=>"v", "Г"=>"g", "Д"=>"d",
		    "Е"=>"e", "Ё"=>"yo", "Ж"=>"zh", "З"=>"z", "И"=>"i", 
		    "Й"=>"j", "К"=>"k", "Л"=>"l", "М"=>"m", "Н"=>"n", 
		    "О"=>"o", "П"=>"p", "Р"=>"r", "С"=>"s", "Т"=>"t", 
		    "У"=>"u", "Ф"=>"f", "Х"=>"kh", "Ц"=>"ts", "Ч"=>"ch", 
		    "Ш"=>"sh", "Щ"=>"sch", "Ъ"=>"", "Ы"=>"y", "Ь"=>"", 
		    "Э"=>"e", "Ю"=>"yu", "Я"=>"ya", "а"=>"a", "б"=>"b", 
		    "в"=>"v", "г"=>"g", "д"=>"d", "е"=>"e", "ё"=>"yo", 
		    "ж"=>"zh", "з"=>"z", "и"=>"i", "й"=>"j", "к"=>"k", 
		    "л"=>"l", "м"=>"m", "н"=>"n", "о"=>"o", "п"=>"p", 
		    "р"=>"r", "с"=>"s", "т"=>"t", "у"=>"u", "ф"=>"f", 
		    "х"=>"kh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", "щ"=>"sch", 
		    "ъ"=>"", "ы"=>"y", "ь"=>"", "э"=>"e", "ю"=>"yu", 
		    "я"=>"ya", " "=>"-", "."=>"", ","=>"", "/"=>"-",  
		    ":"=>"", ";"=>"","—"=>"", "–"=>"-"
	    );
		return strtr($str,$tr);
	}
}

?>