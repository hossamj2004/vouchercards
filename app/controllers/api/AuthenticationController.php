<?php
/**
 * Created by PhpStorm.
 * User: StarWallet
 * Date: 7/4/2016
 * Time: 11:10 AM
 */
namespace app\controllers\api;
use app\interfaces\controllerMethods;
use Phalcon\Mvc\Models;
use Phalcon\Mvc\Controller;
/**
 * Class ApiBogoController
 */
class AuthenticationController extends apiBaseController {
    /**
     * @return \Phalcon\Http\ResponseInterface
     * login user into system
     */
    public function loginAction(){

		if( $this->request->has('membership_id')  )
		{
			$client = \Customer::findFirstByid($this->request->get('membership_id'));
			if(!$client){
				$this->error='invalid membership id';
				return $this->setJson();
			}
		 	$email = $client->email;	
		}
		else $email =  $this->request->get('email');
			
        if(
            $this->auth->clientLogin( $email , $this->request->get('password'),$this->request->get())
        ){
            $this->status=true;
            $this->apiSystem->rePrepareSystem($this->auth->user);
            $this->data['system']=$this->apiSystem->getSystemArray();
        } else {
            $this->error=$this->auth->getValidationMessage();
        }
        return $this->setJson();
    }
    /**
     * @return \Phalcon\Http\ResponseInterface
     * login user into system
     */
    public function forgotPasswordAction(){

        $forgetPassword=new \app\library\forgetPassword();
        if ( !$forgetPassword->resetPasswordMessage( $this->request->get('email')))
        {
            $this->error =$forgetPassword->getValidationMessageText();
        }

        return $this->setJson();
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     * check token
     */
    public function checkTokenAction(){

        $forgetPassword=new \app\library\forgetPassword();

        if ( !$forgetPassword->resetPasswordLinkIsValid( $this->request->get('token')))
        {
            $this->error =$forgetPassword->getValidationMessageText();
        }

        return $this->setJson();
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     * set the new password
     */
    //http://localhost/naala/client/#/setPassword/eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXNzYWdlIjoibS5hbHlAbmFxbGEub3JnIiwicGFnZSI6InJlc2V0UGFzc3dvcmQiLCJpc3MiOiJodHRwOlwvXC9yZXdhcmRza2l0LmNvbSIsImlhdCI6MTQ3MDMwNjg3MSwibmJmIjoxNDcwMzA2ODcxLCJleHAiOjE0NzAzOTMyNzF9.XMdP3-wbJyeFVXjvGXx2bF5pXD1kmv8RWi7owq5hYyI
    public function setPasswordAction(){

        $forgetPassword=new \app\library\forgetPassword();

        if (
            !$forgetPassword->resetPasswordLinkIsValid( $this->request->get('token')) ||
            !$forgetPassword->setUserPassword( $this->request->get('password')))
        {
            $this->error =$forgetPassword->getValidationMessageText();
        }

        return $this->setJson();
    }
    public function termsAction(){
    	//todo get from config
    	$this->data['content']="Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.";
        return $this->setJson();
    }



    public function testAction(){
        if($_SERVER['HTTP_HOST'] == "vouchercards.org" || $_SERVER['HTTP_HOST'] == "www.vouchercards.org")
            echo '<base href="http://vouchercards.org/website/test/html/" target="_blank">';
        else
            echo '<base href="'.$this->configuration->application->baseUri.'/public/test/html/" target="_blank">';
        include(__DIR__ .'/apptest.php');
        die();
    }

}
