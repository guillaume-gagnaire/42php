<?php

function _t($key, $params = []) {
	return i18n::get($key, $params);
}

class 							i18n {
	public static 				$__translations = [];
	private static 				$__defaultLanguage = '';
	private static 				$__sessionKey = '__Lang';
	public static 				$__acceptedLanguages = [];
	
	public static function 		load() {
		if (!file_exists(ROOT.'/i18n/'.Conf::get('lang').'.json'))
			return false;
		
		$content = json_decode(file_get_contents(ROOT.'/i18n/'.Conf::get('lang').'.json'), true);
		self::$__translations = array_merge(self::$__translations, $content);
	}
	
	public static function 		get($key, $params = []) {
		if (!isset(self::$__translations[$key])) {
			self::$__translations[$key] = $key;
			file_put_contents(ROOT.'/i18n/'.Conf::get('lang').'.json', json_encode(self::$__translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
			return vsprintf($key, $params);
		}
		return vsprintf(self::$__translations[$key], $params);
	}
	
	public static function 		init() {
        self::$__acceptedLanguages = Conf::get('i18n.languages');
        self::$__defaultLanguage = Conf::get('i18n.default');

		if (Auth::uid()) {
			Conf::set('lang', (isset(Auth::user()['lang']) && in_array(Auth::user()['lang'], self::$__acceptedLanguages)) ? Auth::user()['lang'] : self::$__defaultLanguage);
		} else {
			if (isset($_SESSION[self::$__sessionKey]))
				Conf::set('lang', $_SESSION[self::$__sessionKey]);
			else {
				if (isset($_GET['lang']) && in_array($_GET['lang'], self::$__acceptedLanguages)) {
					Conf::set('lang', $_GET['lang']);
					$_SESSION[self::$__sessionKey] = $_GET['lang'];
				} else {
					// Based on browser
					$languages = explode(',', isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']) : '');
					if (in_array($languages[0], self::$__acceptedLanguages))
						Conf::set('lang', $languages[0]);
					else
						Conf::set('lang', self::$__defaultLanguage);
				}
			}
		}
	}
	
	public static function 		setLang($lang) {
		if (!in_array($lang, self::$__acceptedLanguages))
			$lang = self::$__defaultLanguage;
		Conf::set('lang', $lang);
        Session::set('lang', $lang);
	}
	
	public static function 		values($callback) {
		foreach (self::$__translations as $k => $v)
			$callback($k, $v);
	}
}

?>