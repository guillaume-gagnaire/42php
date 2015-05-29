<?php

include '../init.php';

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