<?php

class                           SocialAuthController extends Controller {
    public function             handle() {
        $service = false;
        if (isset($_GET['service']))
            $service = $_GET['service'];

        if (isset($_GET['redirect'])) {
            Session::set('SocialAuth.Redirect', $_GET['redirect']);
            Session::save();
        }

        if (!$service)
            Redirect::http(Argv::createUrl('login').(isset($_GET['redirect']) ? '?redirect='.urlencode($_GET['redirect']) : ''));

        $auth = new SocialAuth($service);

        $user = $auth->auth();
        if (!$user) {
            $redirect = Session::get('SocialAuth.Redirect', '/');
            Session::remove('SocialAuth');
            Session::save();
            Redirect::http($redirect);
        }

        $existantUser = User::findOne([
            'provider' => $user['provider'],
            'provider_id' => $user['provider_id']
        ]);

        if ($existantUser) {
            Auth::setCurrentUser($existantUser->id, $existantUser);
        } else {
            $u = new User();
            $u->email = $user['email'];
            $u->genre = $user['genre'] == 'male' ? 'M.' : 'Mme.';
            $u->firstname = $user['firstname'];
            $u->lastname = $user['lastname'];
            $u->photo = $user['photo'];
            $u->email_verified = $user['email_verified'];
            $u->provider = $user['provider'];
            $u->provider_id = $user['provider_id'];
            $u->save();
            Auth::setCurrentUser($u->id, $u);
        }

        $redirect = Session::get('SocialAuth.Redirect', '/');
        Session::remove('SocialAuth');
        Session::save();
        Redirect::http($redirect);
    }
}

?>