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

    public $modelName ;
    public $modelPrimaryKey = 'id';
    public $orderBy = null;
    public $modelObj ;
    public $modelObject ;
    public $filterListParameters =[] ;
    public $extraButtons=[
        'edit'=>true,
        'delete' =>true,
        'view' =>true,
    ];
    public $fieldsInList=array();
    public $fieldsInView=array();
    public $fieldsInCreateForm=array();
    public $fieldsInEditForm=array();
    public $fieldsInSearch=array();
    public $fieldsInOrder = [];
    public $folderName= 'superadmin';
    public $redirect= true;
    public $listDefaultFilters=[];
    public $orderEnabled=false;
    public function beforeExecuteRoute($dispatcher){
        parent::beforeExecuteRoute($dispatcher);
        $this->view->setViewsDir($this->configuration->application->viewsDir.$this->folderName.'/');
    }


    public function initialize(){
        if(!isset($modelObj)){
          $this->modelObj= new $this->modelName;
        }
        $this->view->modelName= $this->modelName;
        $this->view->modelNameText= isset( $this->modelNameText ) ? $this->modelNameText  : $this->modelName;
        $this->view->fieldsInList= $this->fieldsInList;
        $this->view->fieldsInSearch= $this->fieldsInSearch;
        $this->view->fieldsInView= $this->fieldsInView;
        $this->view->extraButtons= $this->extraButtons;
        $this->view->fieldsInOrder= $this->fieldsInOrder;
        $this->view->modelPrimaryKey= $this->modelPrimaryKey;
        $this->view->folderName = $this->folderName;
        $this->view->orderEnabled = $this->orderEnabled;
        // Load the current params
        $current_params="";
        $i = 0;
        $getFilers = $_GET ;
        unset( $getFilers['page'] );
        unset( $getFilers['_url'] );
        unset( $getFilers['Limit'] );
        foreach ($getFilers as $key => $value) {
            $current_params .= $key."=".$value . ( (++$i < count($getFilers) ) ?"&" : "");
        }
        $this->view->current_params=$current_params;
        $this->checkView();
    }


    /**
     * Index action
     */
    public function indexAction()
    {


        // clear the search
        if(@$_GET['search_status']=="close"){
          $this->persistent->parameters = null;
        }

        $numberPage = 1;
        $filters=array_merge($this->request->get(),$this->listDefaultFilters);
        if (count($filters) >0) {
            $query = Criteria::fromInput($this->di, $this->modelName, $filters);
            $queryParams= $query->getParams();
            if( !is_array($queryParams ) )
                $queryParams=[];
            //add more than & less than filtering
            foreach( $filters as $key => $value ){
                if(preg_match('/_MoreThan/',$key) && $value !='' ){
                    $condition = str_replace('_MoreThan','>',$key ).' "'.$value.'"' ;
                }
                elseif (preg_match('/_LessThan/',$key) && $value !='' ){
                    $condition = str_replace('_LessThan','<',$key ).' "'.$value.'"' ;
                }
                elseif (preg_match('/_Equal/',$key) && $value !='' ){
                    $condition = str_replace('_Equal','=',$key ).' "'.$value.'"' ;
                }
                elseif (preg_match('/_NotEqual/',$key) && $value !='' ){
                    $condition = str_replace('_NotEqual','!=',$key ).' "'.$value.'"' ;
                }
                elseif (preg_match('/_In/',$key) && $value !='' ){
                    $condition = str_replace('_In',' IN ',$key ).' "'.$value.'"' ;
                }
                elseif (preg_match('/_NotIn/',$key) && $value !='' ){
                    $condition = str_replace('_NotIn',' NOT IN ',$key ).' ('.$value.') ' ;
                }
                if(isset($condition )) {
                    if( isset( $queryParams['conditions'] ) )
                        $queryParams['conditions'].=' AND '.$condition;
                    else
                        $queryParams['conditions']=$condition;
                    $condition=null;
                }
            }
            //add order by
            if(isset($filters['order'])){
                $queryParams['order']=$filters['order'];
            }

            // set the current search value
            $this->persistent->parameters = $queryParams;


        }
        $numberPage = $this->request->getQuery("page", "int");
        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        else{
            if( isset(  $parameters['bind'] ))
                $this->flash->notice("Search result for : ". str_replace('%','', $this->array_read( $parameters['bind'] )) )  ;
            if( isset(  $parameters['order'] ) &&  $parameters['order']  !='' )
                $this->flash->notice("Search result ordered by : ".  $parameters['order'] )  ;
            if(isset(  $parameters['bind'] ) || (isset(  $parameters['order'] ) &&  $parameters['order']  !='') ) {
                $this->view->search="show_close_button";
            }

        }
        //this for extra filtration in list page
        $query = Criteria::fromInput($this->di, $this->modelName, $this->filterListParameters);
        $queryParams =$query->getParams();

        if( !is_array($queryParams) )  $queryParams = [];
        $parameters = array_merge($parameters,$queryParams);
        if( !isset($parameters["order"] ) || $parameters["order"] =='') {
            if( !$this->orderBy)
                $parameters["order"] = $this->modelPrimaryKey .' DESC';
            else
                $parameters["order"] =$this->orderBy;
        }

        $objectsList = $this->modelObj->find($parameters);
        $this->view->totalItems=$objectsList->count();
        if (count($objectsList) == 0) {
            $this->flash->notice("Did not find any ".$this->modelName);
        }

        // Set the pagination limit number
        if(isset($_GET['Limit'])){
          $_SESSION['Limit']=$_GET['Limit'];
        }elseif(!isset($_SESSION['Limit'])){
          $_SESSION['Limit']=20;
        }

        $paginator = new Paginator(array(
          "data" => $objectsList,
          "limit"=> $_SESSION['Limit'],
          "page" => $numberPage
        ));
        $this->view->page = $paginator->getPaginate();
    }

    public function viewAction($id)
    {
        $modleName = $this->modelName;
        $resultObj = $modleName::findFirst($id);
        $this->view->resultObj = $resultObj;
    }
    public function view_contentAction($id)
    {
        $modleName = $this->modelName;
        $resultObj = $modleName::findFirst($id);
        $this->view->resultObj = $resultObj;
    }
    public function checkView(){
        $actionName =$this->dispatcher->getActionName()!=''?$this->dispatcher->getActionName():'index';
        if(! $this->view->exists($this->modelName.'/'.$actionName) )
            $this->view->setMainView('admin'.'/'.$actionName);

    }

    public function deleteAction($id)
    {
        $modleName = $this->modelName;
        $resultObj = $modleName::findFirst($id);
        if (!$resultObj) {
            $this->flash->error($this->modelName." was not found");

            return $this->dispatcher->forward(array(
                "controller" => $this->modelName,
                "action" => "index"
            ));
        }

        if (!$resultObj->delete()) {

            foreach ($resultObj->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "controller" => $this->modelName,
                "action" => "index"
            ));
        }

        $this->flash->success($this->modelName." was deleted successfully");

        return $this->dispatcher->forward(array(
            "controller" => $this->modelName,
            "action" => "index"
        ));
    }

    /**
     * Display the creation form and handle saving
     */
    public function newAction()
    {
        $modelName =$this->modelName;
        if(!$this->modelObject){
          $modelObj = new $modelName();
          $this->modelObject=$modelObj;
        }else{
          $modelObj= $this->modelObject;
        }
        $this->view->resultObj = $modelObj;
        $Form = new superforms\adminForm($modelObj);
        $Form->addFieldsArray($this->fieldsInCreateForm);
        // get all the params
        foreach ($_GET as $key => $value) {
            if(property_exists($modelObj,$key)==true){
                $modelObj->$key=$value;
            }
        }

        $this->forms->set('Form', $Form);
        if ( $this->request->isPost() ) {
            if ($Form->isValid($this->request->getPost()) == false) {
                foreach ($Form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $Form->bind( $_POST ,$modelObj );
                if( !$modelObj->saveAndCommitFromArray(array_merge_recursive ( $_POST ,  self::GetPostedFiles() )) ){
                    $this->flash->error($modelObj->getMessages());
                }
                else
                {
                   $modelPrimaryKey = $this->modelPrimaryKey;
                   if($this->redirect){
                       return $this->response->redirect($this->folderName.'/'. $this->modelName.'/view/'.$modelObj->$modelPrimaryKey.($this->view->current_params?'?'.$this->view->current_params:''));
                   } else {
                       return $modelObj;
                   }
                }
            }
        }
    }

    /**
     * @param $modelObj
     * @return mixed
     * insert many to many data using
     */
    public function insertRelatedManyToManyData($fieldsList,$modelObj){
        foreach($fieldsList as $item)
        {
            if( isset($item['type']) && $item['type'] == 'manyToMany'){
                if( !$modelObj->insertRelatedManyToManyData($_POST[$item['field']],$item['field']) )
                    return false ;
            }
        }
        return true;
    }

    /**
     * For editing the data
     */
    public function editAction($id)
    {
        $modleName= $this->modelName;
        $modelObj = $modleName::findFirst($id);
        $this->view->resultObj = $modelObj;
        $Form = new superforms\adminForm($modelObj);
        $Form->addFieldsArray($this->fieldsInEditForm);
        $this->forms->set('Form', $Form);
        $data =array_filter( $_POST );
        if ( $this->request->isPost() ) {
            if ($Form->isValid($data) == false) {
                foreach ($Form->getMessages() as $message) {

                    $this->flash->error($message);
                }
            } else {
                $Form->bind( $data ,$modelObj );
                if(!$modelObj->saveAndCommitFromArray( array_merge_recursive ($_POST,self::GetPostedFiles() ) )){
                    $this->flash->error($modelObj->getMessages());
                }
                else
                {
                    $modelPrimaryKey = $this->modelPrimaryKey;
                    if($this->redirect){
                        return $this->response->redirect($this->folderName.'/'. $this->modelName.'/view/'.$modelObj->$modelPrimaryKey.($this->view->current_params?'?'.$this->view->current_params:''));
                    } else {
                        return $modelObj;
                    }
                }
            }
        }
    }
    /**
     * Searches for resultObj
     */
    public function searchAction()
    {
        $this->orderByField();
        $Form = new superforms\adminForm();
        $Form->addFieldsArray($this->fieldsInSearch);
        $this->forms->set('Form', $Form);

        $this->view->resultObj = $this->modelObj;
        $this->persistent->parameters = null;
    }


    /**
     * @param $arr
     * @return mixed
     * general function that used to make sure $_POST is not containing any sql injection
     */
    public function sanitizeArray($arr){
        if( !is_array($arr)) {
            $filter = new Filter();
            return $filter->sanitize($arr, "string");
        }
        foreach( $arr as $key=> $item  ) {
            $santiziedArr[$key] = $this->sanitizeArray($item);
        }
        return $santiziedArr;
    }



    //--------------------------------------------------------------
    //Forms realted code
    //--------------------------------------------------------------
    /**
     * @param $arr
     * @param $allowed
     * @return array
     * this function is used to filter array and only get allowed data from it
     * it is user to filter $_POST to make user it have a secure data
     */
    public function filterArr($arr,$allowed){
        return array_intersect_key($arr, array_flip($allowed));
    }


    public  function simpleInit(){
        $modelName=$this->modelName;
        $this->fieldsInList = $modelName::getAttributes();
        $this->fieldsInView = $modelName::getAttributes();
        $this->fieldsInSearch = $modelName::getAttributes();
        $this->fieldsInOrder = $modelName::getAttributes();
        $this->fieldsInCreateForm = $modelName::getAttributes([$this->modelPrimaryKey]);
        $this->fieldsInEditForm=$modelName::getAttributes([$this->modelPrimaryKey]);
    }

    public function setAttributeAction($attribute,$value,$objectId){
        $modelName =$this->modelName;
        $object =$modelName::findFirst($objectId);
        if( $object ){
            $object->$attribute=$value;
            if( $object->save() )
                return $this->response->redirect($this->folderName.'/'. $this->modelName );
            else
                $this->flash->error($object->getMessages());
        }
        else
            $this->flash->error($this->flash->error('Object not found'));
    }

    public function orderByField(){
        if( count( $this->fieldsInOrder ) > 0) {
            $orderByArray=[];
            foreach(   $this->fieldsInOrder as $orderField){
                if( isset( $orderField['orderPos'] ) ) {
                    $orderPos = $orderField['orderPos'];
                    $orderByArray[]= ['id' => $orderField['field'].' '.$orderPos, 'name' =>( ($orderField['key'] &&$orderField['key'] !='') ?$orderField['key'] :$orderField['field']).' '.$orderPos];
                }
                else{
                    $orderPos = ' ASC';
                    $orderByArray[]= ['id' => $orderField['field'].' '.$orderPos, 'name' =>( ($orderField['key'] &&$orderField['key'] !='') ?$orderField['key'] :$orderField['field']).' '.$orderPos];
                    $orderPos = ' DESC';
                    $orderByArray[]= ['id' => $orderField['field'].' '.$orderPos, 'name' =>( ($orderField['key'] &&$orderField['key'] !='') ?$orderField['key'] :$orderField['field']).' '.$orderPos];
                }
             }

            $this->fieldsInSearch[]= array('field' => 'order', 'key' => 'order', 'type' => 'select',
                'selectData' => array($orderByArray, 'id', 'name')) ;
        }
    }

    // save any model data from array POST or Files
    public function saveFromArray($modelName,$data){
        //set post to be like i submited from image controller
        $modelObject= new $modelName();
        if(  !$modelObject->saveAndCommitFromArray($data) ){
            $this->flash->error($modelObject->getMessages());
            return false ;
        }
        return true;
    }


    /**
     * @return array
     * instead using
     * $_FILES we use this function to get code in a simple way
     */

    public static function GetPostedFiles()
    {
        /* group the information together like this example
        Array
        (
            [attachments] => Array
            (
                [0] => Array
                (
                    [name] => car.jpg
                    [type] => image/jpeg
                    [tmp_name] => /tmp/phpe1fdEB
                    [error] => 0
                    [size] => 2345276
                )
            )
            [jimmy] => Array
            (
                [0] => Array
                (
                    [name] => 1.jpg
                    [type] => image/jpeg
                    [tmp_name] => /tmp/phpx1HXrr
                    [error] => 0
                    [size] => 221041
                )
                [1] => Array
                (
                    [name] => 2 ' .jpg
                    [type] => image/jpeg
                    [tmp_name] => /tmp/phpQ1clPh
                    [error] => 0
                    [size] => 47634
                )
            )
        )
        */

        $Result = array();
        $Name = array();
        $Type = array();
        $TmpName = array();
        $Error = array();
        $Size = array();
        foreach($_FILES as $Field => $Data)
        {
            foreach($Data as $Key => $Val)
            {
                $Result[$Field] = array();
                if(!is_array($Val))
                    $Result[$Field] = $Data;
                else
                {
                    $Res = array();
                    self::GPF_FilesFlip($Res, array(), $Data);
                    $Result[$Field] += $Res;
                }
            }
        }

        return $Result;
    }

    private static function GPF_ArrayMergeRecursive($PaArray1, $PaArray2)
    {
        // helper method for GetPostedFiles
        if (!is_array($PaArray1) or !is_array($PaArray2))
            return $PaArray2;
        foreach ($PaArray2 AS $SKey2 => $SValue2)
            $PaArray1[$SKey2] = self::GPF_ArrayMergeRecursive(@$PaArray1[$SKey2], $SValue2);
        return $PaArray1;
    }

    private static function GPF_FilesFlip(&$Result, $Keys, $Value)
    {
        // helper method for GetPostedFiles
        if(is_array($Value))
        {
            foreach($Value as $K => $V)
            {
                $NewKeys = $Keys;
                array_push($NewKeys, $K);
                self::GPF_FilesFlip($Result, $NewKeys, $V);
            }
        }
        else
        {
            $Res = $Value;
            // move the innermost key to the outer spot
            $First = array_shift($Keys);
            array_push($Keys, $First);
            foreach(array_reverse($Keys) as $K)
                $Res = array($K => $Res); // you might think we'd say $Res[$K] = $Res, but $Res starts out not as an array
            $Result = self::GPF_ArrayMergeRecursive($Result, $Res);
        }
    }
    function array_read($arr, $seperator = ', ', $ending = ' and '){
        $retStr = '<ul>';
        if (is_array($arr)){
            foreach ($arr as $key=>$val){
                if (is_array($val)){
                    $retStr .= '<li>' . $key . ' = ' . pp($val) . '</li>';
                }else{
                    $retStr .= '<li>' . $key . ' = ' . $val . '</li>';
                }
            }
        }
        $retStr .= '</ul>';
        return $retStr;
    }
}
