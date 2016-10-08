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
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\Url;

class ApiSystem  {

    public $userType='visitor';
    public $jwtToken;
    public $jwtSession;
    public $client=false;
    public function __construct()
    {
        $this->auth =  \Phalcon\Di::getDefault()->getShared('auth');
        $this->request =  \Phalcon\Di::getDefault()->getShared('request');
    }

    /**
     * byHossam
     *
     * set vars
     *  customer
     *  jwtToken
     *  jwtSession
     *    from current jwtToken
     */
    public function prepareSystem(){
        //here i convert jwt to $_session
        if ($this->request->has('jwtToken')) {
            $this->jwtToken = $this->request->get('jwtToken');
            $this->jwtSession = $this->auth->decode($this->jwtToken,'api');
        }
        $this-> prepareSystemVarsFromSession();
    }

    public function prepareSystemVarsFromSession(){
        if( isset($this->jwtSession ) && $this->jwtSession){
            $this->client = \Customer::FindFirst( $this->jwtSession->userID );
        }
        $this->setUserTypeForACL();
    }

    /**
     * this function run on each page load
     * it set customer , and some related data
     */
    public function setUserTypeForACL(){
        if(isset( $this->client) && $this->client )
        {

            $this->userType = 'client';
            $this->isLogged = true ;
        }else{
            $this->userType = 'visitor';
            $this->isLogged = false ;
        }
    }


    public function generateJwtSession(){
        return $this->auth->encode((array) $this->jwtSession,'api' );
    }


    /**
     * @param bool $customer
     * @param bool $facebookToken
     * set jwtsession with the new data
     */
    public function rePrepareSystem($user=false){
        if( $user )
            $this->client=$user;
        if( !isset( $this->jwtSession )  )
            $this->jwtSession = json_decode('{}');
        $this->jwtSession->userID = $this->client->id;
        $this->prepareSystemVarsFromSession();
        $this->jwtToken=$this->auth->encode( $this->jwtSession,'api');
    }

    public function getSystemArray($toReturn=['all']){
        $result=[];
        $result['client']=
            $this->client->getSpecialDataArray(
                array_merge(\Customer::getAttributes(['password'],true),
                    [

                        //other data to be added on request
                    ]
                )
            );
        $result['jwtToken']= $this->generateJwtSession();
        $result['jwtTokenExpireTime']= date('Y-m-d H:i:s' , @time() +24*60*60 ) ;
        $result['userType']= $this->userType;
        $result['serverTime']= date('Y-m-d H:i:s');
        return $result;

    }
}
