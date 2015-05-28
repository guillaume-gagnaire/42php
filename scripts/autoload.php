<?php

/*
** Autoload
**
** PSR-0 like designed
**
** For example, in the "core" folder, the class "Google_User_Auth"
** will be loaded in the /core/Google/User/Auth.php file.
*/

spl_autoload_register(function($classname){
	$folders = ['core', 'controllers', 'models', 'lib'];
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