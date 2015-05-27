<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


// Basics
define('ROOT', realpath(dirname(__FILE__).'/../'));
include ROOT.'/scripts/autoload.php';

// Chargement de la configuration globale
$confToLoad = Dir::read(ROOT.'/config', true, '*.php');
foreach ($confToLoad as $file)
	include $file;
Conf::load(ROOT.'/config/global.json');

// Initialisation du multilangue
i18n::init();

if (isset($_GET['lang']) && in_array($_GET['lang'], i18n::$__acceptedLanguages)) {
	Conf::set('oldlang', Conf::get('lang'));
	Conf::set('lang', $_GET['lang']);
}

i18n::load('front');

// Construction du tableau d'arguments
include ROOT.'/scripts/argv.php';

Conf::set('url', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

?>