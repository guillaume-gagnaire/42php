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

    public function         logout() {
        $url = '/';
        if (isset($_GET['redirect']))
            $url = urldecode($_GET['redirect']);
        Auth::logout($url);
    }

    public function         register() {
        $errors = [];

        if (isset($_POST['email'], $_POST['password'], $_POST['password2'])) {
            // check if mail is valid
            if (!strlen($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
                $errors[] = _t("L'adresse email n'est pas valide");
            // check if mail isn't used
            if (!sizeof($errors)) {
                $user = User::findOne([
                    'email' => $_POST['email']
                ]);
                if ($user)
                    $errors[] = _t("L'adresse email est déjà utilisée");
            }
            if (!strlen($_POST['password']))
                $errors[] = _t("Vous devez saisir un mot de passe");
            if ($_POST['password'] != $_POST['password2'])
                $errors[] = _t("Les mots de passe ne correspondent pas");
            if (!sizeof($errors)) {
                $user = new User();
                $user->email = $_POST['email'];
                $user->setPassword($_POST['password']);
                $user->save();
                if ($user->id) {
                    Auth::setCurrentUser($user->id, $user);
                    Redirect::http(Conf::get('auth.users.path-after-register'));
                }
            }
        }
        return View::render('register',[
            'errors' => $errors,
            'email' => isset($_POST['email']) ? $_POST['email'] : '',
            'password' => isset($_POST['password']) ? $_POST['password'] : '',
            'password2' => isset($_POST['password2']) ? $_POST['password2'] : ''
        ]);
    }

    public function         passwordForgot() {

    }
}

?>