<?php

class                           SocialAuthController extends Controller {
    public function             handle() {
        $service = false;
        if (isset($_GET['service']))
            $service = $_GET['service'];

        if (isset($_GET['redirect'])) {
            Session::set('SocialAuth.Redirect', $_GET['redirect']);
        }

        if (!$service)
            Redirect::http(Argv::createUrl('login').(isset($_GET['redirect']) ? '?redirect='.urlencode($_GET['redirect']) : ''));

        $auth = new SocialAuth($service);

        $user = $auth->auth();

        var_dump($user);



        $redirect = Session::get('SocialAuth.Redirect', '/');
        Session::remove('SocialAuth');
        Session::save();
        //Redirect::http($redirect);
    }
}

?>