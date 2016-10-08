<?php
$loader = new \Phalcon\Loader();
require_once __DIR__ . '/../library/vendor/autoload.php';

/**
 * We're a registering a set of namespaces taken from the configuration file
 */
$loader->registerNamespaces(array(
    'app\controllers'        => __DIR__ . '/../controllers/',
    'app\library'   		 => $config->application->libraryDir,
    'app\aclSystem'   		 => $config->application->libraryDir.'acl/',
    'app\interfaces'         => $config->application->interfacesDir,
    'Phalcon\Forms\Element'  => $config->application->libraryDir.'custom_fields/',
));
/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
    	$config->application->libraryDir,
        $config->application->libraryDir.'security/',
        $config->application->libraryDir.'acl/',
        $config->application->libraryDir.'custom_fields/',
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->logDir
    )
)->register();
	