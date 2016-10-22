<?php
/**
 *
 * byHossam
 *
 * the reason for this file is to have most common data in one object in the whole sytsem
 * so it is easy to get functionality like developer data , home page url or user type  in any place in website
 * it also contain the login and the logout
 *
 */
namespace app\library;
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\Url;
use app\library\Translate;

class SuperAdminSystem
{
    public $admin;
    public $isLoged=false;
    public $session;
    public $userType='visitor';
    public function __construct()
    {
        $this->session =  \Phalcon\Di::getDefault()->getShared('session');
    }

    /**
     * this function run on each page load
     * it set developer , and some related data
     */
    public function prepareSuperAdminSystem(){
        if( isset($this->session->adminLogged) ){
            $this->userType='admin';
            $this->isLogged = true;
        }
        else
            $this->isLogged = false;

    }


    /**
     * @param $email
     * @param $password
     * @return bool
     * it simply login with user if he has entered valid email & password
     */
    public function login($email,$password){
        $security = \Phalcon\Di::getDefault()->getShared('security');
        $configuration= \Phalcon\Di::getDefault()->getShared('configuration');
        if( $configuration->adminData['user'] == $email && $configuration->adminData['password']==  $password)
        {
            $this->session->isLogged=1;
            $this->session->adminLogged= true;
            $this->prepareSuperAdminSystem();
            return true;
        }else
            return false;
        return false;
    }


    /**
     * log out user from system
     */
    public function logout(){
        $this->session->adminLogged=false;
        $this->session->isLogged=false;
        unset($this->session->adminId);
        $this->session->destroy() ;
    }

    /**
     * @param bool $full
     * @return string
     * link of homepage is changed depended on userType so this function is used to get homepage
     */
    public function getHomeLink($full= false){
        $url=\Phalcon\Di::getDefault()->getShared('url');
        $url = $full ? $url->getBaseUri()  : '';
        if(  $this->userType == 'admin')
            return $url.'superadmin/dashboard';
        elseif(  substr($this->userType,0,6) == 'admin_'  )
            return $url.'superadmin/Dashboard';
        else
            return $url.'superadmin/Authentication/login';
    }

    //---------------------------------------------------------------
    //Php functionalities needed in volt
    //---------------------------------------------------------------
    public function formatDate($date,$null='Not set'){
        if($date )
        return date('Y-m-d', strtotime( $date ) );
        else return $null;
    }

    /**
     * this is alternative function for php array_flip that work on objects
     * @param $array
     * @return array
     */
    public function arrayFlip($array){
        return $array;
    }
    public function round($number,$perc=2){
        return round($number,$perc);
    }

    public function arrayGroup($arr,$groupBy)
    {
        $result = array();
        foreach ($arr as $data) {
            $id = $data->$groupBy->name;
            if (isset($result[$id])) {
                $result[$id][] = $data;
            } else {
                $result[$id] = array($data);
            }
        }
        return $result;
    }
}
