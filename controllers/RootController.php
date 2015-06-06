<?php

class                                   RootController extends Controller {

    /**
    Gère les urls à une entrée inconnues
     */
    public function                     redirect($p) {
        // Test pour une url traduite
        global $argv;
        $route = Argv::globalRoute($argv, JSON::toArray(ROOT.'/config/routes.json'));
        if ($route && $route['controller'] != 'RootController@redirect') {
            $url = Argv::createUrl($route['route']['name'], $route['route']['params']);
            if (sizeof($_GET))
                $url .= '?'.http_build_query($_GET);
            Redirect::http($url);
        }
        $path = Argv::parse($_SERVER['REQUEST_URI']);

        return Controller::run('PagesController@process', ['path' => '/'.implode('/', $path)]);
    }
}

?>