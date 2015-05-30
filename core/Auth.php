<?php

class                               Auth {
    public static function          uid() {
        if (Auth::logged())
            return Session::get('user.id');
        return 0;
    }

    public static function          logged() {
        $uid = Session::get('user.id', false);
        if ($uid !== false)
            return true;
        return false;
    }

    public static function          admin() {
        return Session::get('user.admin', false);
    }

    public static function          user() {
        return Session::get('user', false);
    }

    public static function          mustBeLogged($needToBeAdmin = false) {
        if (self::logged() && (self::admin() || !$needToBeAdmin))
            return;
        Redirect::http(Argv::createUrl('login').'?redirect='.urlencode(Conf::get('url')));
    }

    public static function 			setCurrentUser($uid, $user = false) {
        if (!$user)
            $user = new User($uid);
        if ($user->id == $uid) {
            $data = $user->toJson(true); // Get user data for session (with the admin value)
            Session::set('user', $data);
            Session::save();
        }
    }

    public static function          login($username, $password) {
        if (!strlen($username) || !strlen($password))
            return false;

        $user = User::findOne([
            'email' => $username
        ]);
        if (!$user)
            return false;
        if (!$user->testPassword($password))
            return false;
        self::setCurrentUser($user->id, $user);
        return true;
    }

    public static function          logout($redirect = '/') {
        Session::remove('user');
        Redirect::http($redirect);
    }
}

?>