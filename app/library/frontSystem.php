<?php
/**
 *
 * byHossam
 *
 * the reason for this file is to have most common data in one object in the whole sytsem
 * so it is easy to get functionality like client data , home page url or user type  in any place in website
 * it also contain the login and the logout
 *
 */
namespace app\library;
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\Url;
use app\library\Translate;

class frontSystem
{
    public $currentUser;
    public $client;
    public $isLoged=false;
    public $session;
    public $userType='visitor';
    public function __construct()
    {
        $this->session =  \Phalcon\Di::getDefault()->getShared('session');
    }

    /**
     * this function run on each page load
     * it set client , and some related data
     */
    public function preparefrontSystem(){
        if(isset($this->session->frontAdminLogged) && !empty($this->session->clientID)){
            $this->client = \Client::findFirstById($this->session->clientID);
            $this->isLoged = true;
            $this->userType= 'client';
        }
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     * it simply login with user if he has entered valid email & password
     */
    public function login($email,$password){
        $security = \Phalcon\Di::getDefault()->getShared('security');
        $client = \Client::findFirstByEmail($email);
        if($client ) {

                if($security->checkHash( $password,$client->password ))
                {
                    $this->session->frontAdminLogged=1;
                    $this->session->clientID= $client->id;
                    $client->last_login=date("Y-m-d H:i:s");
                    $client->save();
                    $this->preparefrontSystem();
                    return true;
                }else
                    false;

        }
        return false;
    }

    /**
     * set clientID with null
     */
    public function logout(){
        $this->session->frontAdminLogged=false;;
        $this->session->clientID=null;
    }

    /**
     * @param bool $full
     * @return string
     * link of homepage is changed depended on userType so this function is used to get homepage
     */
    public function getHomeLink($full= false){
        $url=\Phalcon\Di::getDefault()->getShared('url');
        $url = $full ? $url->getBaseUri()  : '';
        if( $this->userType == 'client'  )
             return $url.'contact';
        if( $this->userType == 'visitor'  )
            return $url.'authentication/login';
    }

    //---------------------------------------------------------------
    //Php functionalities needed in volt
    //---------------------------------------------------------------
    public function formatDate($date,$null='Not set'){
        if($date )
        return date('Y-m-d', strtotime( $date ) );
        else return $null;
    }
}