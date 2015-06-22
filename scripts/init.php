<?php

if (!isset($_SERVER['HTTP_HOST']))
    $_SERVER['HTTP_HOST'] = 'cli';
if (!isset($_SERVER['REQUEST_URI'])) {
    $p = [];
    if (isset($argv)) {
        $_GET['argv'] = [];
        foreach ($argv as $i => $a)
            if ($i) {
                $p[] = 'argv[' . ($i - 1) . ']=' . urlencode($a);
                $_GET['argv'][] = $argv;
            }
    }
    $_SERVER['REQUEST_URI'] = '/?'.implode('&', $p);
}

// Basics
define('ROOT', realpath(dirname(__FILE__).'/../'));
include ROOT.'/scripts/autoload.php';

// Chargement de la configuration globale
$confToLoad = Dir::read(ROOT.'/config', true, '*.php');
foreach ($confToLoad as $file)
	include $file;
Conf::load(ROOT.'/config/global.json');

Conf::set('inspector.starttime', microtime(true));

if (Conf::get('debug', false)) {
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

// Initialisation du multilangue
i18n::init();

if (isset($_GET['lang']) && in_array($_GET['lang'], i18n::$__acceptedLanguages)) {
	Conf::set('oldlang', Conf::get('lang'));
	Conf::set('lang', $_GET['lang']);
}

i18n::load();

// Construction du tableau d'arguments
include ROOT.'/scripts/argv.php';

Conf::set('url', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);

// Page hash
global $argv;
$pageHash = '/'.implode('/', $argv);
if (sizeof($_GET))
	$pageHash .= '?' . http_build_query($_GET);
Conf::set('page.hash', sha1($pageHash));

?>