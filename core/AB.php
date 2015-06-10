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
		
		$ab = new ABPageView();
		$ab->date = date('Y-m-d H:i:s');
		$ab->clicked = 0;
		$ab->path = $_SERVER['REQUEST_URI'];
		$ab->userid = Session::get('user.id', 0);
		$ab->sessionid = Session::$id;
		$ab->pagehash = Conf::get('page.hash', '');
		$ab->click_date = '0000-00-00 00:00:00';
		$ab->param = '';
		$ab->file = $page;
		$ab->save();
		
		Conf::set('ab.pageview.id', $ab->id);
		
		return View::$func($page, $params);
	}
	
	public static function 		link($link, $param = '') {
		return Argv::createUrl('ab').'?clickOn='.Conf::get('ab.pageview.id', 0).'&redirect='.urlencode($link).'&p='.urlencode($param);
	}
	
	public static function 		click($opts, $param = '') {
		$link = Argv::createUrl('ab').'?clickOn='.Conf::get('ab.pageview.id', 0).'&p='.urlencode($param);
		return "$.ajax({url: '$link'})";
	}
}

?>