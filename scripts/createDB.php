<?php

include 'init.php';


$queries = [
	'Sessions' => 	'CREATE TABLE IF NOT EXISTS `Sessions` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`data` text,
						`expire` int(11),
						PRIMARY KEY (`id`),
						KEY `expire` (`expire`)
					)',
	'Applications' => 	'CREATE TABLE IF NOT EXISTS `Applications` (
							`id` int(11) NOT NULL AUTO_INCREMENT,
							`key` text,
							`secret` text,
							`device` text,
							PRIMARY KEY (`id`)
						)'
];

foreach ($queries as $k => $v) {
	echo "Creating $k ... ";
	Db::exec($v);
	echo "Done.\n";
}

?>