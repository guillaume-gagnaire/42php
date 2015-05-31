<?php

class                                   RootController extends Controller {

    /**
    Gère les urls à une entrée inconnues
     */
    public function                     redirect($p) {
        if (strlen($p->path)) {


            // Test pour une url traduite
            global $argv;
            $route = Argv::globalRoute($argv, JSON::toArray(ROOT.'/config/routes.json'));
            if ($route && $route['controller'] != 'RootController@redirect') {
                $url = Argv::createUrl($route['route']['name'], $route['route']['params']);
                if (sizeof($_GET))
                    $url .= '?'.http_build_query($_GET);
                Redirect::http($url);
            }

            return Controller::run('PagesController@process', ['path' => '/'.$p->path]);
        }

        return Controller::run('PagesController@process', ['path' => '/']);
    }
}

?>