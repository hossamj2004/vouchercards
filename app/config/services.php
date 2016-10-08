<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Dispatcher,
    Phalcon\Mvc\Dispatcher as MvcDispatcher,
    Phalcon\Events\Manager as EventsManager,
    Phalcon\Mvc\Dispatcher\Exception as DispatchException;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Security;
use app\library\Sql\SqlLog;
use app\library\Sql\SqlProfiler;
use app\library\System;
use app\library\frontSystem;
use app\library\SuperAdminSystem;
use app\library\ApiSystem;
use app\library\ClientApiSystem;
use app\library\DriverApiSystem;
use app\aclSystem\apiClientWebAclSystem;
use app\aclSystem\superAdminAclSystem;
use app\aclSystem\apiAclSystem;
use app\library\Auth;
use app\library\Translate;
use Phalcon\Forms\Manager as FormsManager;
use Phalcon\Flash\Direct as FlashDirect;
/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();
/**
 * Load our custome router.
 */
$di->set('router', function(){
    return require __DIR__ . '/routes.php';
}, true);

/**
 * The URL component is used to generate all kind of urls in the application
 */
$di->set('url', function () use ($config) {
    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
}, true);



/**
 * Setting up the view component
 */
$di->set('view', function () use ($config) {

    $view = new View();

    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines(array(
        '.volt' => function ($view, $di) use ($config) {

            $volt = new VoltEngine($view, $di);

            $volt->setOptions(array(
                'compiledPath' => $config->application->cacheDir,
                'compiledSeparator' => '_',
                'compileAlways' => true
            ));

            return $volt;
        },
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ));

    return $view;
}, true);

/**
 * Database connection is created based in the parameters defined in the configuration file
 */
$di->set('db', function () use ($config) {
    return new DbAdapter(array(
        'host' => $config->database->host,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname' => $config->database->dbname,
        'charset'  => 'utf8'
    ));
});

/**
 * If the configuration specify the use of metadata adapter use it or use memory otherwise
 */
$di->set('modelsMetadata', function () {
    return new MetaDataAdapter();
});
$di->setShared('modelsManager', function() {
    return new Phalcon\Mvc\Model\Manager();
});
/**
 * Start the session the first time some component request the session service
 */
$di->setShared('session', function () {
    //session_set_cookie_params(0, '/', '.' . 'rewardskit.com');
    $session = new SessionAdapter();
    $session->start();

    return $session;
});

/*
*   @desc load configuration object ot use it anywhere.
*/
$di->setShared('configuration',$config);

/*
*   @desc handle any request process before routing {ex: secuirty, url processing}.
*/
 $di->set('dispatcher', function() {

     //Create an EventsManager
     $eventsManager = new EventsManager();

     //Attach a listener
     $eventsManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) {

         //Handle 404 exceptions
         if ($exception instanceof DispatchException) {
             $dispatcher->forward(array(
                 'controller' => 'index',
                 'action' => 'index'
             ));
             return false;
         }

         //Alternative way, controller or action doesn't exist
         if ($event->getType() == 'beforeException') {
             switch ($exception->getCode()) {
                 case \Phalcon\Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                 case \Phalcon\Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                     $dispatcher->forward(array(
                         'controller' => 'index',
                         'action' => 'index'
                     ));
                     return false;
             }
         }
     });

     $dispatcher = new \Phalcon\Mvc\Dispatcher();

     //Bind the EventsManager to the dispatcher
     $dispatcher->setEventsManager($eventsManager);
     $dispatcher->setDefaultNamespace('app\controllers');
     return $dispatcher;

 }, true);
/*
*   @desc make an object of request to handle any request.
*/
$di->set('request', function(){
    $request = new Request();
    return $request;
});
/*
*   @desc make an object of response to handle any request response.
*/
$di->set('response', function(){
    $response = new Response();
    return $response;
});
$di->set('security', function(){

    $security = new Security();

    //Set the password hashing factor to 12 rounds
    $security->setWorkFactor(12);

    return $security;
}, true);

$di->set('sqlLogger', function(){
    $sqlLogger = new SqlLog();
    return $sqlLogger;
});

$di->set('sqlProfiler', function(){
    $sqlProfiler = new SqlProfiler();
    return $sqlProfiler;
});


$di->set('flash', function () {
    $flash = new FlashDirect(
        array(
            'error'   => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice'  => 'alert alert-info',
            'warning' => 'alert alert-warning'
        )
    );

    return $flash;
});

//for front admin
$di['forms'] = function() {
    return new FormsManager();
};

$di['frontSystem'] = function() {
    return new frontSystem();
};

// for super
$di['superAdminSystem'] = function() {
    return new SuperAdminSystem();
};

//ACL
$di['apiClientWebAclSystem'] = function() {
    return new apiClientWebAclSystem();
};
$di['superAdminAclSystem'] = function() {
    return new superAdminAclSystem();
};
$di['apiAclSystem'] = function() {
    return new apiAclSystem();
};

// for api
$di['apiSystem'] = function() {
    return new apiSystem();
};

// for client Api System
$di['clientApiSystem'] = function() {
    return new clientApiSystem();
};

// for driver Api System
$di['driverApiSystem'] = function() {
    return new driverApiSystem();
};

// for authentication
$di['auth'] = function() {
    return new Auth();
};
