<?php

namespace app\models;

class Actions extends \li3_fieldwork\extensions\data\Model {

	public $validates = [
		'key_id' => [
			'notEmpty'
		],
		'on' => [
			'notEmpty'
		]
	];
}

?>