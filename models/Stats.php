<?php

class                       Stats extends Model {
    public                  $ip;
    public                  $user_id;
    public                  $path;
    public                  $date;
    public                  $lang;

    public static function  log() {
        $n = new Stats;
        $n->ip = IP::get();
        $n->date = date('Y-m-d H:i:s');
        $n->user_id = Session::get('user.id', 0);
        $n->path = $_SERVER['REQUEST_URI'];
        $n->lang = Conf::get('lang');
        $n->save();
    }
}

?>