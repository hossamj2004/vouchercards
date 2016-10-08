<?php
//$http_origin = $_SERVER['HTTP_ORIGIN'];
//
//if ($http_origin == "http://www.rewardskit.com" || $http_origin=="http://rewardskit.com" || $http_origin == "http://localhost" || $http_origin == "http://develop.rewardskit.com")
//{
//    header("Access-Control-Allow-Origin: *");
//}
date_default_timezone_set('Africa/Cairo');
error_reporting(E_ALL);
ini_set('display_errors','On');
ini_set('log_errors','On');
ini_set('error_log',__DIR__.'/../app/logs/erros.log');

try {

    /**
     * Read the configuration
     */
    $config = include __DIR__ . "/../app/config/config.php";
    if($config->debug){ini_set('display_errors','On');}

    /**
     * Read auto-loader
     */
    include __DIR__ . "/../app/config/loader.php";

    /**
     * Security stuf
     */
    include __DIR__."/../app/library/security/JWT.php";

    /**
     * Read services
     */
    include __DIR__ . "/../app/config/services.php";

    /**
     * Handle the request
     */
    $application = new \Phalcon\Mvc\Application($di);
    echo $application->handle()->getContent();
} catch (\Exception $e) {
    if($config->debug){
        echo $e->getMessage();echo '<br>';
        echo $e->xdebug_message;
        var_dump($e);

    }else{

        $exceptionLogger = new \Phalcon\Logger\Adapter\File($config->application->logDir.'/exception.log');
        $exceptionDetails  = $e->getMessage();
        $exceptionDetails .= "\n".'===========================================================================================================================================================================================================================================================================================================================';
        $exceptionLogger->log($exceptionDetails, \Phalcon\Logger::INFO);

        $data['data']['msg'] = $e->getMessage();
        app\library\responseHandler::getResponse($data)->send();
        die();
        echo 'Exception has been occurred, please check exception log file.';
    }

}