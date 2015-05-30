<?php

class                       AuthController extends Controller {
    public function         login() {
        $error = false;
        if (isset($_POST['email'], $_POST['password'])) {
            $error = true;
            if (strlen($_POST['email']) && strlen($_POST['password'])) {
                $ret = Auth::login($_POST['email'], $_POST['password']);
                if ($ret) {
                    $url = '/';
                    if (isset($_GET['redirect']))
                        $url = urldecode($_GET['redirect']);
                    Redirect::http($url);
                }
            }
        }
        return View::render("login", [
            'error' => $error
        ]);
    }
}

?>