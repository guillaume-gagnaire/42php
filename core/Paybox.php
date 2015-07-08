<?php

class 								Paybox {
	private static 					$test = false;
	private static 					$debug = false;
	private static 					$directPlus = true;
	private static 					$log = true;
	
	private static					$site = '';
	private static 					$rang = '';
	private static 					$authid = '';
	private static 					$cle = '';
	
	private static 					$urlProd = 'https://ppps.paybox.com/PPPS.php';
	private static 					$urlSecours = 'https://ppps1.paybox.com/PPPS.php';
	private static 					$urlTest = 'https://preprod-ppps.paybox.com/PPPS.php';
	
	private static 					$versionPayboxDirect = '00103';
	private static 					$versionPayboxDirectPlus = '00104';
	
	private static					$devises = [
		'EUR' => '978',
		'USD' => '840',
		'CFA' => '952'
	];
	
	private static function 		log($url, $params, $response) {
		$server = '\e[31mInvalid ('.$url.')\e[39m';
		if ($url == self::$urlProd)
			$server = "\e[34mProd\e[39m";
		if ($url == self::$urlSecours)
			$server = "\e[33mRescue\e[39m";
		if ($url == self::$urlTest)
			$server = "\e[36mTest\e[39m";
		
		$forbiddenParams = [
			'VERSION',
			'SITE',
			'RANG',
			'CLE',
			'CVV'
		];
		
		$obfuscateParams = [
			'PORTEUR'
		];
		
		foreach ($forbiddenParams as $item) {
			if (isset($params[$item]))
				unset($params[$item]);
			if (isset($response[$item]))
				unset($response[$item]);
		}
		
		foreach ($obfuscateParams as $item) {
			if (isset($params[$item]))
				$params[$item] = str_repeat('*', strlen($params[$item]) - 3) . substr($params[$item], -3);
			if (isset($response[$item]) && strlen($response[$item]) > 3)
				$response[$item] = str_repeat('*', strlen($response[$item]) - 3) . substr($response[$item], -3);
		}
		
		$color = $response['CODEREPONSE'] == '00000' ? "\e[32m" : "\e[31m";
		$defaultColor = "\e[39m";
		
		$userid = Auth::uid();
		if ($userid) {
			$user = new User($userid);
			if (is_null($user->id)) {
				$user->email = 'unknown';
				$user->genre = '';
				$user->firstname = 'unknown';
				$user->lastname = '';
			}
		} else {
			$user = new User();
			$user->email = 'unknown';
			$user->genre = '';
			$user->firstname = 'unknown';
			$user->lastname = '';
		}
		
		$request = $params['TYPE'];
		switch ($request) {
		case '00001':
			$request = "\e[34mAutorisation\e[39m";
			break;
		case '00002':
			$request = "\e[32mCapture\e[39m";
			break;
		case '00003':
			$request = "\e[34mAutorisation\e[39m + \e[32mCapture\e[39m";
			break;
		case '00014':
			$request = "\e[31mRemboursement\e[39m";
			break;
		}
		
		$logfile = ROOT.'/logs/paybox/'.date('Y-m-d').'.log';
		if (!is_dir(dirname($logfile)))
			mkdir(dirname($logfile), 0755, true);
		
		$logstr = "-----------------\n\n".date('Y-m-d H:i:s')."\tRequest to Paybox server: $server : $request\n\t\t\tUser data:\n\t\t\t\tID: $userid   Name: {$user->genre} {$user->lastname} {$user->firstname}   Mail: {$user->email}\n\t\t\tRequest data:\n\t\t\t\t".json_encode($params)."\n\t\t\tResponse: $color".self::getErrorMsg($response['CODEREPONSE'])."$defaultColor\n\t\t\t\t".json_encode($response)."\n\n";
		file_put_contents($logfile, $logstr, FILE_APPEND);
	}
	
	private static function 		processCall($url, $params) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_COOKIESESSION, true);
		
		$trame = http_build_query($params, '', '&');
		if (self::$debug)
			echo "$url => $trame\n\n";
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $trame);
		
		$response = curl_exec($curl);
		curl_close($curl);
		
		parse_str($response, $data);
		
		if (self::$log)
			self::log($url, $params, $data);
		
		return $data;
	}
	
	private static function 		call($type, $params = []) {
		$data = [];
		$data['VERSION'] = self::$directPlus ? self::$versionPayboxDirectPlus : self::$versionPayboxDirect;
		$data['TYPE'] = $type;
		$data['SITE'] = self::$site;
		$data['RANG'] = self::$rang;
		$data['CLE'] = self::$cle;
		$data['NUMQUESTION'] = '10'.Text::random(4, '0123456789').str_pad(time() % 10000, 4, '0');
		
		foreach ($params as $k => $v)
			$data[$k] = $v;
		
		$data['ACTIVITE'] = '024';
		$data['DATEQ'] = date('dmYHis');
		$data['PAYS'] = '';
		
		if (self::$test) {
			return self::processCall(self::$urlTest, $data);
		} else {
			$returned = self::processCall(self::$urlProd, $data);
			if (in_array($returned['CODEREPONSE'], ['00001', '00097', '00098']))
				return self::processCall(self::$urlSecours, $data);
			return $returned;
		}
	}
	
	private static function 		formatMoney($amount) {
		return intval($amount * 100);
	}
	
	private static function 		cleanNumber($str) {
		return preg_replace('/[^0-9]/', '', $str);
	}
	
	public static function 			getErrorMsg($code) {
		$icode = intval($code);
		if ($icode >= 100 && $icode < 200)
			return _t('paybox.00100');
		return _t('paybox.'.$code);
	}
	
	public static function 			refund($amount, $ref, $trans, $appel, $devise = 'EUR') {
		$ret = self::call('00014', [
			'MONTANT' => self::formatMoney($amount),
			'DEVISE' => self::$devises[$devise],
			'REFERENCE' => $ref,
			'NUMTRANS' => $trans,
			'NUMAPPEL' => $appel
		]);
		
		return [
			'valid' => $ret['CODEREPONSE'] == '00000',
			'error' => $ret['CODEREPONSE'],
			'errorMsg' => self::getErrorMsg($ret['CODEREPONSE']),
			'authcodes' => [
				'trans' => isset($ret['NUMTRANS']) ? $ret['NUMTRANS'] : '',
				'appel' => isset($ret['NUMAPPEL']) ? $ret['NUMAPPEL'] : ''
			]
		];
	}
	
	public static function 			auth($amount, $ref, $cardno, $exp, $cvc, $devise = 'EUR') {
		$ret = self::call('00001', [
			'MONTANT' => self::formatMoney($amount),
			'DEVISE' => self::$devises[$devise],
			'REFERENCE' => $ref,
			'PORTEUR' => self::cleanNumber($cardno),
			'DATEVAL' => self::cleanNumber($exp),
			'CVV' => self::cleanNumber($cvc)
		]);
		
		return [
			'valid' => $ret['CODEREPONSE'] == '00000',
			'error' => $ret['CODEREPONSE'],
			'errorMsg' => self::getErrorMsg($ret['CODEREPONSE']),
			'authcodes' => [
				'trans' => isset($ret['NUMTRANS']) ? $ret['NUMTRANS'] : '',
				'appel' => isset($ret['NUMAPPEL']) ? $ret['NUMAPPEL'] : ''
			]
		];
	}
	
	public static function 			capture($amount, $ref, $trans, $appel, $devise = 'EUR') {
		$ret = self::call('00002', [
			'MONTANT' => self::formatMoney($amount),
			'DEVISE' => self::$devises[$devise],
			'REFERENCE' => $ref,
			'NUMTRANS' => $trans,
			'NUMAPPEL' => $appel
		]);
		
		return [
			'valid' => $ret['CODEREPONSE'] == '00000',
			'error' => $ret['CODEREPONSE'],
			'errorMsg' => self::getErrorMsg($ret['CODEREPONSE']),
			'authcodes' => [
				'trans' => isset($ret['NUMTRANS']) ? $ret['NUMTRANS'] : '',
				'appel' => isset($ret['NUMAPPEL']) ? $ret['NUMAPPEL'] : ''
			]
		];
	}
	
	public static function 			authcapture($amount, $ref, $cardno, $exp, $cvc, $devise = 'EUR') {
		$ret = self::call('00003', [
			'MONTANT' => self::formatMoney($amount),
			'DEVISE' => self::$devises[$devise],
			'REFERENCE' => $ref,
			'PORTEUR' => self::cleanNumber($cardno),
			'DATEVAL' => self::cleanNumber($exp),
			'CVV' => self::cleanNumber($cvc)
		]);
		
		return [
			'valid' => $ret['CODEREPONSE'] == '00000',
			'error' => $ret['CODEREPONSE'],
			'errorMsg' => self::getErrorMsg($ret['CODEREPONSE']),
			'authcodes' => [
				'trans' => isset($ret['NUMTRANS']) ? $ret['NUMTRANS'] : '',
				'appel' => isset($ret['NUMAPPEL']) ? $ret['NUMAPPEL'] : ''
			]
		];
	}
	
	public static function 			credit($amount, $ref, $cardno, $exp, $cvc, $devise = 'EUR') {
		$ret = self::call('00004', [
			'MONTANT' => self::formatMoney($amount),
			'DEVISE' => self::$devises[$devise],
			'REFERENCE' => $ref,
			'PORTEUR' => self::cleanNumber($cardno),
			'DATEVAL' => self::cleanNumber($exp),
			'CVV' => self::cleanNumber($cvc)
		]);
		
		return [
			'valid' => $ret['CODEREPONSE'] == '00000',
			'error' => $ret['CODEREPONSE'],
			'errorMsg' => self::getErrorMsg($ret['CODEREPONSE']),
			'authcodes' => [
				'trans' => isset($ret['NUMTRANS']) ? $ret['NUMTRANS'] : '',
				'appel' => isset($ret['NUMAPPEL']) ? $ret['NUMAPPEL'] : ''
			]
		];
	}
}

?>