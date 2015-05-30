<?php

include 'init.php';


$queries = [
	'Sessions' => 	'CREATE TABLE IF NOT EXISTS `Sessions` (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`data` text,
						`expire` int(11),
						PRIMARY KEY (`id`),
						KEY `expire` (`expire`)
					) DEFAULT CHARSET=utf8',
    'Applications' => 	'CREATE TABLE IF NOT EXISTS `Applications` (
							`id` int(11) NOT NULL AUTO_INCREMENT,
							`key` text,
							`secret` text,
							`device` text,
							PRIMARY KEY (`id`)
						) DEFAULT CHARSET=utf8',
    'User' => 	'CREATE TABLE IF NOT EXISTS `User` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `email` text,
                    `password` text,
                    `firstname` text,
                    `lastname` text,
                    `genre` text,
                    `registered` datetime,
                    `admin` tinyint(1),
                    `slug` text,
                    `lang` text,
                    PRIMARY KEY (`id`)
                ) DEFAULT CHARSET=utf8'
];

foreach ($queries as $k => $v) {
	echo "Creating $k ... ";
	Db::exec($v);
	echo "Done.\n";
}

?>