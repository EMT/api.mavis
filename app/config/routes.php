<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2013, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * The routes file is where you define your URL structure, which is an important part of the
 * [information architecture](http://en.wikipedia.org/wiki/Information_architecture) of your
 * application. Here, you can use _routes_ to match up URL pattern strings to a set of parameters,
 * usually including a controller and action to dispatch matching requests to. For more information,
 * see the `Router` and `Route` classes.
 *
 * @see lithium\net\http\Router
 * @see lithium\net\http\Route
 */
use lithium\net\http\Router;
use lithium\core\Environment;
use lithium\action\Response;
use app\models\Actions;


Router::connect('/actions/put.json', 'Actions::put');
Router::connect('/actions/get.json', 'Actions::get');
Router::connect('/actions/tidy', 'Actions::tidy');



// Router::connect('/actions/put.json', [], function($request) {
// 	$action = null;

// 	if (!empty($request->data)) {
// 		$data = $request->data;

// 		if ($data['type'] === 'on') {
// 			// make sure this key isn’t already on
// 			if (!Actions::count(['conditions' => ['key_id' => $data['key_id'], 'off' => 0], 'limit' => 1])) {
// 				$action = Actions::create($data + ['on' => time()]);
// 				$action->save();
// 			}
// 		}
// 		else if ($data['type'] === 'off') {
// 			$action = Actions::first([
// 				'conditions' => [
// 					'key_id' => $data['key_id'],
// 					'off' => 0
// 				],
// 				'order' => ['on' => 'DESC']
// 			]);

// 			if ($action) {
// 				$action->off = time();
// 				$action->save();
// 			}
// 		}
// 	}

// 	return new Response([
// 		'headers' => [
// 			'Access-Control-Allow-Origin' => '*',
// 			'Content-type' => 'application/json'
// 		],
// 		'status'=> ($action && $action->id) ? 200 : 500,
// 		'data' => [
// 			'response' => ($action && $action->id) ? 'true' : 'false'
// 		],
// 		'type' => 'json'
// 	]);
// });


// Router::connect('/actions/get.json', [], function($request) {
// 	$timeout_limit = 30; // timeout limit in seconds

// 	// tidy up any open keys that have reached the timeout limit
// 	$query = 'UPDATE actions SET `off` = `on` + ' . $timeout_limit . ' ';
// 	$query .= 'WHERE `on` <= ' . (time() - $timeout_limit) . ' AND `off` = 0';
// 	Actions::connection()->read($query);

// 	$conditions = [
// 		'on' => ['>=' => $request->query['from']],
// 		'off' => ['>' => 0]
// 	];

// 	if (!empty($request->query['to'])) {
// 		$conditions = ['off' => ['<=' => $request->query['to']]] + $conditions;
// 	}

// 	$actions = Actions::all([
// 		'conditions' => $conditions,
// 		'order' => ['on' => 'ASC']
// 	]);

// 	return new Response([
// 		'headers' => [
// 			'Access-Control-Allow-Origin' => '*',
// 			'Content-type' => 'application/json'
// 		],
// 		'status'=> 200,
// 		'data' => $actions->data(),
// 		'type' => 'json'
// 	]);
// });


/**
 * Here, we are connecting `'/'` (the base path) to controller called `'Pages'`,
 * its action called `view()`, and we pass a param to select the view file
 * to use (in this case, `/views/pages/home.html.php`; see `app\controllers\PagesController`
 * for details).
 *
 * @see app\controllers\PagesController
 */
Router::connect('/', 'Pages::view');


/**
 * Add the testing routes. These routes are only connected in non-production environments, and allow
 * browser-based access to the test suite for running unit and integration tests for the Lithium
 * core, as well as your own application and any other loaded plugins or frameworks. Browse to
 * [http://path/to/app/test](/test) to run tests.
 */
if (!Environment::is('production')) {
	Router::connect('/test/{:args}', array('controller' => 'lithium\test\Controller'));
	Router::connect('/test', array('controller' => 'lithium\test\Controller'));
}



?>