<?php


include 'init.php';

echo '<base target="_parent" />';

extract($_GET);

$confFile = "<?php

Conf::set('pdo.dsn', 'mysql:host=$host;dbname=$dbname');
Conf::set('pdo.user', '$user');
Conf::set('pdo.pass', '$pass');
Conf::set('pdo.prefix', '$prefix');

?>";
file_put_contents(ROOT.'/config/db.php', $confFile);
include ROOT.'/config/db.php';


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
                    `photo` text,
                    `email_verified` tinyint(1),
                    PRIMARY KEY (`id`)
                ) DEFAULT CHARSET=utf8',
    'Page' => 	'CREATE TABLE IF NOT EXISTS `Page` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `path` text,
                    `file` text,
                    `title` text,
                    `description` text,
                    `keywords` text,
                    `image` text,
                    PRIMARY KEY (`id`)
                ) DEFAULT CHARSET=utf8',
    'Article' => 	'CREATE TABLE IF NOT EXISTS `Article` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `lang` text,
                        `title` text,
                        `intro` text,
                        `content` text,
                        `image` text,
                        `keywords` text,
                        `date` date,
                        `author` int(11),
                        `category` int(11),
                        `slug` text,
                        `enabled` tinyint(1),
                        PRIMARY KEY (`id`)
                    ) DEFAULT CHARSET=utf8',
    'Category' => 	'CREATE TABLE IF NOT EXISTS `Category` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `lang` text,
                        `title` text,
                        `description` text,
                        `keywords` text,
                        `slug` text,
                        PRIMARY KEY (`id`)
                    ) DEFAULT CHARSET=utf8',
    'Stats' => 	'CREATE TABLE IF NOT EXISTS `Stats` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_id` int,
                    `date` datetime,
                    `path` text,
                    `ip` text,
                    `lang` text,
                    PRIMARY KEY (`id`)
                ) DEFAULT CHARSET=utf8',
    'ABPageView' => 	'CREATE TABLE IF NOT EXISTS `ABPageView` (
		                    `id` int(11) NOT NULL AUTO_INCREMENT,
		                    `userid` int(11),
		                    `date` datetime,
		                    `file` text,
		                    `path` text,
		                    `pagehash` text,
		                    `sessionid` text,
		                    `param` text,
		                    `clicked` tinyint(1),
		                    `click_date` datetime,
		                    PRIMARY KEY (`id`)
		                ) DEFAULT CHARSET=utf8'
];

echo "Creating tables ... <br />";
foreach ($queries as $k => $v) {
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$k<br />";
	Db::exec($v);
}

echo "<br />Creating admin user ...";
$previousUsers = User::find();
foreach ($previousUsers as $prev)
    $prev->delete();

$u = new User();
$u->admin = true;
$u->email = $adminmail;
$u->firstname = $firstname;
$u->lastname = $lastname;
$u->registered = date('Y-m-d H:i:s');
$u->setPassword($adminpass);
$u->save();
echo " Done.";

echo '<br /><br />You can now <a href="/admin" class="button">login</a> into the admin panel. Don\'t forget to delete /install.php and /scripts/createDB.php.';

?>