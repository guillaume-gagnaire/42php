<?php

class                   AdminController extends Controller {
    public function     index($p) {
        Auth::mustBeLogged(true);
    }
}

?>