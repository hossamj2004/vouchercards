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

class ClientApiSystem  extends ApiSystem {
    public $userType='visitor';
    public $jwtToken;
    public $jwtSession;
    public $driver=false;
    public function __construct()
    {
        $this->auth =  \Phalcon\Di::getDefault()->getShared('auth');
        $this->request =  \Phalcon\Di::getDefault()->getShared('request');
        $this->response =  \Phalcon\Di::getDefault()->getShared('response');
    }

    /**
     * set customerID with null
     */
    public function logout(){
        $this->session->isLogged=false;;
        $this->session->clientID = null;
    }

    public function getSystemArray($toReturn=['all']){
        $result=[];
        $result['client']=
            $this->client->getSpecialDataArray(
                array_merge(\Client::getAttributes(['password'],true),
                    [
                        ['field' => 'getFirstImageUrl(default)', 'key' => 'profile_image'],
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

    public function prepareSystem(){


      if( isset( getallheaders()['jwtToken'] ) ){
        $_POST['jwtToken']=getallheaders()['jwtToken'];
      }

      //here i convert jwt to $_session
      if ($this->request->has('jwtToken')||isset($_POST['jwtToken'])) {
          $this->jwtToken =  $this->request->get('jwtToken') ? $this->request->get('jwtToken') : $_POST['jwtToken'];
          $this->jwtSession = $this->auth->decode($this->jwtToken,'api');
      }
      $this-> prepareSystemVarsFromSession();
    }

    public function prepareSystemVarsFromSession(){
        if(isset($this->jwtSession->userID)){
            $this->client = \Client::FindFirst( $this->jwtSession->userID );
        }
        $this->setUserTypeForACL();
    }

    /**
     * this function run on each page load
     * it set customer , and some related data
     */
    public function setUserTypeForACL(){
        // to set customer type same as website
        if(isset( $this->client) && $this->client )
        {
            $this->userType = 'client';
            $this->isLogged = true ;
        }
        else
        {
            $this->userType = 'visitor';
            $this->isLogged = false ;
        }
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
}
