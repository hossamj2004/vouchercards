<?php
namespace app\controllers\api;

use app\controllers\superadmin\AdminBaseController;
use Faker\Factory;
use Phalcon\Exception;
use Phalcon\Filter;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Url;
use Phalcon\Acl\Adapter;
use Phalcon\Acl;
use Phalcon\Security;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria;
class apiBaseController extends Controller{
    public $folderName = 'api';

    /**
     * @param $dispatcher
     * function for making sure url is secured with hmac
     */
    public function hmacSecure($dispatcher)
    {

        $dataToHash=$this->request->get();
        if(isset( $dataToHash['hashOutput'] ))
            $clientHash= $dataToHash['hashOutput'];
        else
            $clientHash=false;
        unset($dataToHash['hashOutput']);
        //unset($dataToHash['jwttoken']);
        unset($dataToHash['_url']);
        unset($dataToHash[ 'PHPSESSID']);
        unset($dataToHash[ 'js_login_output']);
        foreach( $dataToHash as  $key => $item ){
            if( ! is_array($dataToHash[$key] )) {
                if( (0 === strpos($dataToHash[$key], 'fbm_')) )
                    unset($dataToHash[$key]);

                if( is_array( $item )) {
                    foreach( $item as $k=> $i )
                        $dataToHash[ utf8_encode($key.  '['.$k.']' ) ]= $i;
                }
            }

        }
        // Create the hash using the private key
        if( isset( $this->configuration->sdk->starwalletApp->enableHmac  ) && $this->configuration->sdk->starwalletApp->enableHmac  ){
            if(  count( $dataToHash ) > 0 ) {
                $serverHash = hash_hmac('sha1', json_encode($dataToHash,JSON_UNESCAPED_UNICODE),$this->configuration->sdk->starwalletApp->privateKey);
                if(!( $clientHash == $serverHash ) ){
                    $this->error = serialize($dataToHash);
                    $this->dieJson();
                }
            }
        }else {
            if(  $this->request->get('debug_post')  ){
                $this->error =$this->request->get();
                $this->dieJson();
            }

        }
    }

    /**
     * @param $dispatcher
     * main function that any api run on it first
     */
    public function beforeExecuteRoute($dispatcher){
        $this->hmacSecure($dispatcher);

        //prepare system
        $this->apiSystem->prepareSystem();

        //prepare auth class
        if( $this->apiSystem->client)
           $this->auth->setCurrentUser($this->apiSystem->client);

        //run acl
        if($this->error = $this->apiClientWebAclSystem->ApiErrorIfNoAccess() ){
            $this->errorType='authentication_error';
            $this->dieJson();
        }


    }


    /**
     * @var array
     * Json data return from functions bellow
     * simply convert my array to json and view it
     */
    public $data=[];
    public $status=false;
    public $error=false;
    public $errorType='alert';
    public function setJson(){
        header('Content-Type: application/json');
        if($this->error){
            $result['status'] = false;
            $result['data'] = $this->data;
            $result['data']['error']  = $this->error;
            if(isset($this->errorType))
                $result['data']['errorType']  = $this->errorType;
        }
        else{

            $result['status'] = true;
            $result['data']  = $this->data;
        }
        return $this->response->setJsonContent($result);
    }


    /**
     * here i return json style
     */
    public function dieJson(){
        header('Content-Type: application/json');
        if($this->error){
            $result['status'] = false;
            $result['data'] = $this->data;
            $result['data']['error']  = $this->error;
            if(isset($this->errorType))
                $result['data']['errorType']  = $this->errorType;
        }
        else{
            $result['status'] = true;
            $result['data']  = $this->data;
        }
        echo $this->response->setJsonContent($result)->getContent();
        die();
    }
    public function urlDecode(array $array) {
        $convertedArray = array();
        foreach($array as $key => $value) {
            if( !is_array( $value ) )
                $value = urldecode($value);
            $convertedArray[$key] = $value;
        }
        return $convertedArray;
    }
    public function filterArr($arr,$allowed){
        return array_intersect_key($arr, array_flip($allowed));
    }

    /**
     * make a general function for returning data
     */
    public $modelObj ;
    public $modelObject ;
    public $modelName ;
    public $activeApis=[
        'list'=>false,
        'details'=>false,
        'save'=>false
    ] ;
    public $fieldsInDetails=[];
    public $fieldsInList=[];
    public $dataToSave=[];
    public $viewFilter=[];
    public $listFilter=[];
    public $saveFilter=[];
    public $generalFilter=[];
    public $allowedSaveParams=[];
    public function listAction(){
        if( !$this->activeApis['list'] ){
            $this->error ='Access denied';
            return $this->setJson();
        }

        $modelName = $this->modelName;
        $data = $modelName::findByQuery($modelName::getQueryByArray( array_merge($this->generalFilter,$this->listFilter) ) );
        $this->data['items']= $modelName::getSpecialDataArrayForArray($data,$this->fieldsInList);
        return $this->setJson();
    }
    public function detailsAction(){
        if( !$this->activeApis['details'] ){
            $this->error ='Access denied';
            return $this->setJson();
        }
        $modelName = $this->modelName;
        $resultObj = $modelName::findFirstByQuery($modelName::getQueryByArray( array_merge($this->generalFilter, $this->viewFilter) ));
        if(!$resultObj){
            $this->error='invalid filters';
            return $this->setJson();
        }
        $this->data['item']= $resultObj->getSpecialDataArray($this->fieldsInDetails);
        return $this->setJson();
    }
    public function saveAction(){
        if( !$this->activeApis['save'] ){
            $this->error ='Access denied';
            return $this->setJson();
        }
        $modelName = $this->modelName;
        if( count( array_merge($this->generalFilter,$this->saveFilter) ) == 0 )
            $resultObj = new $modelName();
        else
            $resultObj = $modelName::findFirst(array_merge($this->generalFilter,$this->saveFilter));

        if( !$resultObj ){
            $this->error='invalid filters';
        }
        $arrayToSave= count(  $this->allowedSaveParams )== 0 ? $this->dataToSave :  array_intersect_key($this->dataToSave,array_flip($this->allowedSaveParams));
        if( !$resultObj->saveAndCommitFromArray( $arrayToSave ))
        {
            $this->error=$resultObj->getValidationMessageText();
        }
        return $this->setJson();
    }

    public function simpleInit(){
        $modelName = $this->modelName;
        $this->listFilter=$this->request->get();
        $this->viewFilter=$this->request->get();
        $this->saveFilter=$this->request->has('id') ? ['id'=>$this->request->get('id')] : [] ;
        $this->dataToSave=array_merge_recursive ($this->request->get(),AdminBaseController::GetPostedFiles() );
        $this->allowedSaveParams= [];
        $this->fieldsInList = $modelName::getAttributes([],true );
        $this->fieldsInDetails = $modelName::getAttributes([],true );
    }
}
