<?php
/**
 * Created by PhpStorm.
 * User: komrakov
 * Date: 6/23/14
 * Time: 11:35 PM
 */

defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__DIR__) ); // var/www/steam_market_parser

use Phalcon\DI\FactoryDefault\CLI as CliDI, Phalcon\CLI\Console as ConsoleApp;

require __DIR__ . '/../vendor/autoload.php';

$di = new CliDI();

// Default router
$di->set('router', [
    'className' => '\Phalcon\CLI\Router',
]);

// Default dirs
$di->set('loader', [
    'className' => '\Phalcon\Loader',
    'calls' => [
        ['method' => 'registerDirs', 'arguments' => [
            ['type' => 'parameter', 'value' => [
                //'controllers' => APPLICATION_PATH . '/controllers/',
                'tasks'       => APPLICATION_PATH . '/app/common/tasks/',
                'components'  => APPLICATION_PATH . '/app/common/components/',
                'models'      => APPLICATION_PATH . '/app/models/'
            ]]
        ]],
        ['method' => 'register'],
    ],
]);
$di->get('loader');

// Default Task and Action
$di->set('dispatcher', array(
        'className' => '\Phalcon\CLI\Dispatcher',
        'calls'     => array(
            array('method' => 'setDefaultTask', 'arguments' => array(
                array('type' => 'parameter', 'value' => 'Main'),
            )),
            array('method' => 'setDefaultAction', 'arguments' => array(
                array('type' => 'parameter', 'value' => 'main'),
            )),
        ),
    )
);

$di->setShared('mongo', function() {
    $mongo = new MongoClient();
    return $mongo->selectDb("steam");
});

$di->set('collectionManager', function(){
    return new Phalcon\Mvc\Collection\Manager();
});

// Console application
$console = new ConsoleApp($di);

// Parse command line parameters "console.php taskName/actionName param1=value1 param2=value2"
$handle_params = array();
array_shift($argv); // Skip "console.php"
list($task, $action) = explode('/', array_shift($argv)); // Parse "taskName/actionName"
foreach($argv as $param) {
    list($name, $value) = explode('=', $param);
    $handle_params[$name] = $value;
}
$handle_params['module'] = null; // Without modules
$handle_params['task'] = $task;
$handle_params['action'] = $action;

// Run
$console->handle($handle_params);
