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
					$action = Actions::create($data + ['on' => time()]);
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
					$action->off = time();
					$action->save();
				}
			}
		}

		$this->response->headers('Access-Control-Allow-Origin', '*');

		return $this->render([
			'json' => [
				'response' => ($action && $action->id) ? 'true' : 'false'
			], 
			'status'=> 200
		]);
	}

	public function get() {
		$timeout_limit = 30; // timeout limit in seconds

		// tidy up any open keys that have reached the timeout limit
		$query = 'UPDATE actions SET `off` = `on` + ' . $timeout_limit . ' ';
		$query .= 'WHERE `on` <= ' . (time() - $timeout_limit) . ' AND `off` = 0';
		Actions::connection()->read($query);

		$conditions = [
			'on' => ['>=' => $this->request->query['from']],
			'off' => ['>' => 0]
		];

		if (!empty($this->request->query['to'])) {
			$conditions = ['off' => ['<=' => $this->request->query['to']]] + $conditions;
		}

		$actions = Actions::all([
			'conditions' => $conditions,
			'order' => ['on' => 'ASC']
		]);

		$this->response->headers('Access-Control-Allow-Origin', '*');

		return $this->render([
			'json' => $actions->data(), 
			'status'=> 200
		]);
	}
}

?>