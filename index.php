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
    $route = [
        'controller' => 'RootController@redirect',
        'params' => ''
    ];
}

Stats::log();

echo Controller::run($route['controller'], $route['params']);

?>