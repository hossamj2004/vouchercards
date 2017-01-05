<?php
/**
 * Created by PhpStorm.
 * User: Hossam
 * Date: 16/4/15
 *  this file responsible for general functionalities that could be used in front admin controllers like check if user has acces to page
 */

namespace app\controllers\superadmin;
use Faker\Factory;
use Phalcon\Exception;
use Phalcon\Filter;
use Phalcon\Mvc\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
use  Phalcon\Mvc\Url;
use Phalcon\Acl\Adapter;
use Phalcon\Acl;
use Phalcon\Security;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;
class AdminBaseController extends SuperAdminBaseController{


    /**
     * @return \Phalcon\Http\ResponseInterface
     * set the new password
     */
    //http://localhost/naala/client/#/setPassword/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXNzYWdlIjoibS5hbHlAbmFxbGEub3JnIiwicGFnZSI6InJlc2V0UGFzc3dvcmQiLCJpc3MiOiJodHRwOlwvXC9yZXdhcmRza2l0LmNvbSIsImlhdCI6MTQ3MDMwNjg3MSwibmJmIjoxNDcwMzA2ODcxLCJleHAiOjE0NzAzOTMyNzF9.XMdP3-wbJyeFVXjvGXx2bF5pXD1kmv8RWi7owq5hYyI
    public function setPasswordAction(){

        $forgetPassword=new \app\library\forgetPassword();
        if ( !$forgetPassword->resetPasswordLinkIsValid( $this->request->get('token')))
        {
            $this->error =$forgetPassword->getValidationMessageText();
            return;
        }
        if (
            !$forgetPassword->resetPasswordLinkIsValid( $this->request->get('token')) ||
            !$forgetPassword->setUserPassword( $this->request->get('password')))
        {
            $this->error =$forgetPassword->getValidationMessageText();
        }

        return ;
    }
}
