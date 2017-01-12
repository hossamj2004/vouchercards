<?php
/**
 * Created by PhpStorm.
 * User: rudy
 * Date: 3/18/15
 * Time: 12:40 PM
 */
$router = new Phalcon\Mvc\Router(false);
/**
 *  The below routes belong to the general section.
 */
$router->add('/', array(
    'namespace' => 'app\controllers\front',
    'controller' => 'index',
     'action' => 'index'
));

/**
 *  The below routes belong to the front admin ( brand and developer admin ) section.
 */
$router->add('/:controller/:action/:params', array(
    'namespace' => 'app\controllers\front',
    'controller' => 1,
    'action' => 2,
    'params' => 3,
));
$router->add('/:controller', array(
    'namespace' => 'app\controllers\front',
    'controller' => 1
));

/**
 *  The below routes belong to the Super Admin
 */
$router->add('/superadmin', array(
    'namespace' => 'app\controllers\superadmin',
    'controller' => 'index'
));
$router->add('/superadmin/:controller/:action/:params', array(
    'namespace' => 'app\controllers\superadmin',
    'controller' => 1,
    'action' => 2,
    'params' => 3,
));
$router->add('/superadmin/:controller', array(
    'namespace' => 'app\controllers\superadmin',
    'controller' => 1
));


/**
 *  The below routes belong to the API
 */
$router->add('/api', array(
    'namespace' => 'app\controllers\api',
    'controller' => 'index'
));
$router->add('/api/:controller/:action/:params', array(
    'namespace' => 'app\controllers\api',
    'controller' => 1,
    'action' => 2,
    'params' => 3,
));
$router->add('/api/:controller', array(
    'namespace' => 'app\controllers\api',
    'controller' => 1
));

/**
 *  The below routes belong to the API
 */
$router->add('/front', array(
    'namespace' => 'app\controllers\front',
    'controller' => 'index'
));
$router->add('/front/:controller/:action/:params', array(
    'namespace' => 'app\controllers\front',
    'controller' => 1,
    'action' => 2,
    'params' => 3,
));
$router->add('/front/:controller', array(
    'namespace' => 'app\controllers\front',
    'controller' => 1
));

return $router;
