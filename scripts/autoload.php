<?php

/*
** Autoload
**
** PSR-0 like designed
**
** For example, in the "core" folder, the class "Google_User_Auth"
** will be loaded in the /core/Google/User/Auth.php file.
*/
include_once ROOT.'/core/Conf.php';
spl_autoload_register(function($classname){
	$folders = ['core', 'controllers', 'models', 'lib'];
	$site = Conf::get('site');
	if ($site && $site != '')
		$folders[] = 'controllers/'.$site;
	$classname = str_replace('_', '/', $classname);
	foreach ($folders as $folder) {
		$file = ROOT."/$folder/$classname.php";
		if (file_exists($file)) {
			include $file;
			return;
		}
	}
});

?>