<?php

class 							Controller {
	protected 					$html;
	protected					$argc;
	protected					$argv;
	
	public function 			__construct($html, $argc, $argv) {
		foreach (['html', 'argc', 'argv'] as $item)
			$this->$item = $$item;
	}
	
	public static function 		run($name, $params = []) {
		global $argc, $argv, $html;
		
		list($c, $m) = explode('@', $name);
        if (!class_exists($c))
            return '';
		$obj = new $c($html, $argc, $argv);
        if (!method_exists($obj, $m))
            return '';
		return $obj->$m((object)$params);
	}
}

?>