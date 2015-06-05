<?php

class 								Ini {
	public static function 			bytes($val) {
	    $val = trim($val);
	    $last = strtolower($val[strlen($val)-1]);
	    switch($last) {
	        // Le modifieur 'G' est disponible depuis PHP 5.1.0
	        case 'g':
	            $val *= 1024;
	        case 'm':
	            $val *= 1024;
	        case 'k':
	            $val *= 1024;
	    }
	
	    return $val;
	}
	
	public static function 			get($field) {
		return ini_get($field);
	}
	
	public static function 			size($val) {
		return Ini::bytes($val);
	}
}

?>