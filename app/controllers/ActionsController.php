<?php

namespace app\controllers;

use app\models\Actions;
use lithium\action\DispatchException;

class ActionsController extends \lithium\action\Controller {

	public function put() {
		$action = null;

		if (!empty($this->request->data)) {
			$data = $this->request->data;

			if ($data['type'] === 'on') {
				// make sure this key isn’t already on
				if (!Actions::count(['conditions' => ['key_id' => $data['key_id'], 'off' => 0], 'limit' => 1])) {
					$action = Actions::create($data + ['on' => $this->_getTime()]);
					$action->save();
				}
			}
			else if ($data['type'] === 'off') {
				$action = Actions::first([
					'conditions' => [
						'key_id' => $data['key_id'],
						'off' => 0
					],
					'order' => ['on' => 'DESC']
				]);

				if ($action) {
					$action->off = $this->_getTime();
					$action->save();
				}
			}
		}

		return $this->render([
			'json' => [
				'response' => ($action && $action->id) ? 'true' : 'false'
			], 
			'status'=> 200
		]);
	}

	public function get() {
		$timeout_limit = 30000; // timeout limit in seconds

		// tidy up any open keys that have reached the timeout limit
		// $query = 'UPDATE actions SET `off` = `on` + ' . $timeout_limit . ' ';
		// $query .= 'WHERE `on` <= ' . ($this->_getTime() - $timeout_limit) . ' ';
		// $query .= 'AND (`off` = 0 || `off` - `on` > ' . $timeout_limit . ')';
		// Actions::connection()->read($query);

		$conditions = [
			'off' => ['>' => 0]
		];

		if (!empty($this->request->query['from'])) {
			$conditions = [
				'on' => ['>=' => $this->request->query['from']]
			] + $conditions;
		}

		if (!empty($this->request->query['to'])) {
			$conditions = [
				'off' => [
					'<=' => $this->request->query['to'],
					'>' => ['>' => 0]
				]
			] + $conditions;
		}

		$actions = Actions::all([
			'conditions' => $conditions,
			'order' => ['on' => (empty($conditions['on'])) ? 'DESC' : 'ASC'],
			'fields' => [
				'id',
				'key_id',
				'on',
				'`off` - `on` AS duration'
			],
			'limit' => (!empty($this->request->query['limit'])) ? $this->request->query['limit'] : false
		]);

		$actions = $actions->data();
		
		if ($actions && empty($conditions['on'])) {
			usort($actions, function($a, $b) {
			    return $a['on'] - $b['on'];
			});
		}

		return $this->render([
			'json' => $actions, 
			'status'=> 200
		]);
	}


	public function _getTime() {
		return round(microtime(true) * 1000);
	}
}

?>
