<?php

session_start();

/**
 * Facebook
 */
define('FACEBOOK_SDK_V4_SRC_DIR', ROOT.'/lib/facebook/src/Facebook/');
require ROOT.'/lib/facebook/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use Facebook\FacebookRedirectLoginHelper;

/**
 * Google
 */
require_once ROOT.'/lib/google/src/Google/autoload.php';

class                       SocialAuth {
    private                 $provider;

    public function         __construct($provider) {
        $this->provider = $provider;
        if (method_exists($this, 'provider'))
            trigger_error('No such provider.');
    }

    public function         auth() {
        $m = $this->provider;
        return $this->$m();
    }

    /**
     * Return example:
     * {
     *  email
     *  provider
     *  provider_id
     *  firstname
     *  lastname
     *  genre (male || female)
     *  email_verified
     *  photo
     * }
     */
    private function         facebook() {
        if (!Conf::get('auth.social.facebook.enabled', false))
            return false;

        FacebookSession::setDefaultApplication(
            Conf::get('auth.social.facebook.app_id', ''),
            Conf::get('auth.social.facebook.app_secret', '')
        );
        $helper = new FacebookRedirectLoginHelper('http://'.$_SERVER['HTTP_HOST'].Argv::createUrl('socialauth').'?service=facebook&receive');

        try {
            $session = $helper->getSessionFromRedirect();
        } catch (FacebookRequestException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        if (isset($_GET['receive'])) {
            $request = new FacebookRequest($session, 'GET', '/me');
            $response = $request->execute();
            $graphObject = $response->getGraphObject();

            return [
                'email' => $graphObject->getProperty('email'),
                'provider' => 'facebook',
                'provider_id' => $graphObject->getProperty('id'),
                'firstname' => $graphObject->getProperty('first_name'),
                'lastname' => $graphObject->getProperty('last_name'),
                'genre' => $graphObject->getProperty('gender'),
                'email_verified' => $graphObject->getProperty('verified'),
                'photo' => 'http://graph.facebook.com/'.$graphObject->getProperty('id').'/picture?type=large'
            ];
        } else {
            $loginUrl = $helper->getLoginUrl([
                'scope' => 'email'
            ]);
            Redirect::http($loginUrl);
        }
    }

    private function        google() {
        if (!Conf::get('auth.social.google.enabled', false))
            return false;

        $client_id = Conf::get('auth.social.google.client_id', '');
        $client_secret = Conf::get('auth.social.google.client_secret', '');
        $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].Argv::createUrl('socialauth').'?service=google&receive';

        $client = new Google_Client();
        $client->setClientId($client_id);
        $client->setClientSecret($client_secret);
        $client->setRedirectUri($redirect_uri);
        $client->addScope('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.login');

        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $client->getAccessToken();
            $redirect = 'http://'.$_SERVER['HTTP_HOST'].Argv::createUrl('socialauth').'?service=google&receive';
            header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
        }

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $client->setAccessToken($_SESSION['access_token']);
        } else {
            Redirect::http($client->createAuthUrl());
        }

        if ($client->getAccessToken()) {
            $plus = new Google_Service_Oauth2($client);
            $me = $plus->userinfo->get();
            return [
                'email' => $me->email,
                'provider' => 'google',
                'provider_id' => $me->id,
                'firstname' => $me->givenName,
                'lastname' => $me->familyName,
                'genre' => $me->gender,
                'email_verified' => $me->verifiedEmail,
                'photo' => $me->picture
            ];
        }

    }
}

?>