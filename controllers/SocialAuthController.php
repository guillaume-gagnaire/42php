<?php

class                           SocialAuthController extends Controller {
    public function             handle() {
        $service = false;
        if (isset($_GET['service']))
            $service = $_GET['service'];

        $redirect = '/';
        if (isset($_GET['redirect']))
            $redirect = $_GET['redirect'];

        if (!$service)
            Redirect::http(Argv::createUrl('login').'?redirect='.urlencode($redirect));

    }
}

?>