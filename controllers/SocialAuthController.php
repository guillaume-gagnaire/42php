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

        $config_file_path = ROOT.'/lib/hybridauth/config.php';

        // Load lib
        require_once ROOT.'/lib/hybridauth/Hybrid/Auth.php';
        // Starting

        try {
            $hybridauth = new Hybrid_Auth($config_file_path);

            $provider = $hybridauth->authenticate($service);

            var_dump($provider);

        } catch (Exception $e) {

        }
    }
}

?>