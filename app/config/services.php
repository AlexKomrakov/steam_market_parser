<?php
/**
 * Created by PhpStorm.
 * User: komrakov
 * Date: 6/23/14
 * Time: 11:32 PM
 */

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function() use ($config) {
    $url = new \Phalcon\Mvc\Url();
    $url->setBaseUri($config->application->baseUri);
    return $url;
});

/**
 * Setting up the view component
 */
$di->set('view', function() use ($config) {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function() use ($config) {
    return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->name
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function() use ($config) {
    if (isset($config->models->metadata)) {
        $metadataAdapter = 'Phalcon\Mvc\Model\Metadata\\'.$config->models->metadata->adapter;
        return new $metadataAdapter();
    } else {
        return new \Phalcon\Mvc\Model\Metadata\Memory();
    }
});

/**
 * Start the session the first time some component request the session service
 */
$di->set('session', function() {
    $session = new \Phalcon\Session\Adapter\Files();
    $session->start();
    return $session;
});

$di->setShared('mongo', function() {
    $mongo = new MongoClient();
    return $mongo->selectDb("steam");
});

$di->set('collectionManager', function(){
    return new Phalcon\Mvc\Collection\Manager();
});