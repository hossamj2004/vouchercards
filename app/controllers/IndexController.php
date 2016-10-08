<?php
/**
 * @desc use ControllerMethods interface.
 */

namespace app\controllers;
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction(){
        $this->response->setStatusCode(404, 'Not Found');
        $this->view->pick('404/404');
    }
}

