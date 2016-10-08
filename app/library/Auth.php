<?php
/**
 *
 * byHossam
 *
 * the reason for this file is to have most common data in one object in the whole sytsem
 * so it is easy to get functionality like customer data , home page url or user type  in any place in website
 * it also contain the login and the logout
 *
 */
namespace app\library;
use EnticeKit\Core\Customer;
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
class Auth
{
    public $validationMessage='';
    public $customer;
    public function __construct()
    {
    }

    /**
     * @param $email
     * @param $password
     * @return client or bool false
     * used in api system to let user log in and re
     */
    public function clientLogin($email,$password){

        //validate request if user and password is sent as expected and have correct parameters
        if(!$this->validateClientLoginParameters($email,$password))
            return false;

        //get client by email
        $client = \Customer::findFirstByemail($email);
        if( !$client ){
            $this->validationMessage ='Invalid login. Please try again';
            return false;
        }

        //check password if correct
        if ( ! $client->isPasswordCorrect($password) ){
            $this->validationMessage=$client->getValidationMessageText();
            return false;
        }

        //save last login
        $client->last_login = date( 'Y-m-d H:i:s' );
        $client->save();

        //login complete set client and return Client Object
        return $this->setCurrentUser($client);
    }
    /**
     * @param $email
     * @param $passwords
     * @return bool
     * just check if parameters are ok
     */
    public function validateClientLoginParameters($email,$passwords){
        if( !isset( $email )){
            $this->validationMessage='email code is required';
            return false;
        }
        if( !isset( $passwords )){
            $this->validationMessage='Password is required';
            return false;
        }
        return true;
    }
    /**
     * @param $customer
     * @return bool
     * a simple function to set $this->user
     */
    public function setCurrentUser($user){

        //check if account is blocked
        if ( property_exists( $user ,'is_blocked' ) &&  $user->is_blocked ){
            $this->validationMessage=' Your account is blocked';
            return false;
        }
        if( $user ){
            $this->user =$user ;
            return $user;
        }
        else{
            $this->validationMessage = "لا يوجد حساب";
            return false ;
        }
    }
    //----------------------------------------------------------------------------
    // general functions
    //----------------------------------------------------------------------------
    /**
     * @param $message
     * @return bool
     * read text of the message and send it as email to $this->driver
     */
    public function sendUserMessage($title,$message){
         // sendMail (  $this->driver,$title,$message );
        return true;
    }
    public $key ='SkdsjInjdB@4';
    public function encode($message,$page=''){
        if( $page =='api') $expTime= @time() +60*24*60*60;
        elseif( $page=='draws')$expTime= @time() +10*24*60*60;
        else $expTime= @time() +24*60*60;
        $token = array(
            "message" => $message,
            "page" => $page,
            "iss" => "http://starwallet.com",
            "iat" => @time(),
            "nbf" =>  @time(),
            "exp" =>  $expTime,
        );


        $jwt = \JWT::encode($token, $this->key);
        return $jwt;
    }
    public function decode($jwt,$page=''){
        try{
            $decoded =\JWT::decode($jwt, $this->key);
        }catch (\Exception $e){
            return false;
        }
        if($decoded && $decoded->iss=="http://starwallet.com" && $page == $decoded->page  )
            return $decoded->message;
        else{
            $this->validationMessage = $this->language->t('Invalid token');
            return false ;
        }

    }
    public function getValidationMessage(){
        return $this->validationMessage;
    }
}
