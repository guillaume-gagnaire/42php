<?php

class 								Hash {
	public static function 			blowfish($input, $rounds = 7) {
		$salt = "";
	    $salt_chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
	    for ($i = 0; $i < 22; $i++) {
		    $salt .= $salt_chars[array_rand($salt_chars)];
	    }
	    return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
	}
	
	public static function 			same($entered, $original) {
		return crypt($entered, $original) == $original;
	}
}

?>