<?php

include 'scripts/init.php';

Session::$apiMode = false;
Session::init();

Conf::set('route', false);
$route = Argv::route($argv, JSON::toArray(ROOT.'/config/routes.json'));
if (isset($route['route']))
    Conf::set('route', $route['route']);

include ROOT.'/scripts/i18n.php';

if (!$route) {
    Http::throw404(false);
}

echo Controller::run($route['controller'], $route['params']);

?>