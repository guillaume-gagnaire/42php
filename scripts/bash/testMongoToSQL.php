<?php

include '../init.php';

Conf::set('pdo.dsn', "mysql:dbname=test;unix_socket=/tmp/mysql.sock");

Db::get('select 1');

var_dump(Db::where([
	'$or' => [
		'field1' => 'yolo',
		'field2' => [
			'$eq' => 42,
			'$in' => [
				1, 2, 3, 4, 5
			]
		]
	]
]));

?>