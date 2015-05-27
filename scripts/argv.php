<?php

global $argv, $argc;
$argv = Argv::parse(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', Conf::get('argv.offset'));
$argc = sizeof($argv);

?>