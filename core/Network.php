<?php

class 						Network {
	public static function 	call($url, $method = 'GET', $data = array(), $headers = array(), $useragent = 'Nala', $proxy = false) {
		$headers = array_merge(array(
        	'Content-type: application/x-www-form-urlencoded;charset=UTF-8'
        ), $headers);
		$opts = array('http' =>
		    array(
		        'method'  => $method,
		        'header'  => $headers,
		        'content' => http_build_query($data),
		        'ignore_errors' => true,
		        'user_agent' => $useragent
		    )
		);
		
		if ($proxy != false) {
			$opts['http']['proxy'] = $proxy;
			$opts['http']['request_fulluri'] = true;
		}
		
		$context  = stream_context_create($opts);
		
		$result = file_get_contents($url, false, $context);
		if ($result)
			return $result;
		return false;
	}
	
	public static function 	get($url, $headers = array(), $useragent = 'Nala', $proxy = false) {
		return Network::call($url, 'GET', array(), $headers, $useragent, $proxy);
	}
	
	public static function 	post($url, $data = array(), $headers = array(), $useragent = 'Nala', $proxy = false) {
		return Network::call($url, 'POST', $data, $headers, $useragent, $proxy);
	}
	
	public static function 	delete($url, $headers = array(), $useragent = 'Nala', $proxy = false) {
		return Network::call($url, 'DELETE', array(), $headers, $useragent, $proxy);
	}
	
	public static function 	put($url, $data = array(), $headers = array(), $useragent = 'Nala', $proxy = false) {
		return Network::call($url, 'PUT', $data, $headers, $useragent, $proxy);
	}
}

?>