<?php
/**
 * Created by PhpStorm.
 * User: apache_reset_timeout
 * Date: 3/7/16
 *
 * This controller handle the image uploader
 *
 */

namespace app\controllers\superadmin;

//use app\library\Firebase;

class NotificationController extends AdminBaseController{
    public $modelName ='Notification';
    public $modelNameText ='Notification';
    public $modelPrimaryKey = 'id';
    public $extraButtons=[
        'new'=>true,
        'edit'=>false,
        'delete' =>true,
        'view' =>true,
        'details' =>false,
    ];
    public function beforeExecuteRoute($dispatcher){

        parent::beforeExecuteRoute($dispatcher);
        $modelName=$this->modelName;
        $list= $modelName::getAttributes(['id','is_read','message','client_id','type']);
        $list[]=['field' => 'Customer->getFullName', 'key' => 'Client'];
        $view= $modelName::getAttributes(['id','is_read','client_id']);
        $view[]=['field' => 'Customer->getFullName', 'key' => 'Client'];
        $form= [];
        //$form[] =  ['field' => 'topic', 'key' => 'Topic', 'type' => 'hidden','value'=>'returnValue(clients)'];
        $form[] =  ['field' => 'customer_id', 'key' => 'Client',  'type' => 'ajaxSelect','selectData' => [$this->url->getBaseUri() .'superadmin/Notification/getClientsByAjax','id','getFullName']];
        $form[] =  ['field' => 'subject', 'key' => 'Subject', 'type' => 'text'];
        //$form[] =  ['field' => 'image', 'key' => 'image', 'type' => 'image'];
        $form[] =  ['field' => 'message', 'key' => 'Message', 'type' => 'textArea'];
        $form[] =  ['field' => 'type', 'key' => 'Type',  'type' => 'ajaxSelect','selectData' => [$this->url->getBaseUri() .'superadmin/Notification/getTypes','id','name']];

        $this->fieldsInCreateForm = $form;
        $this->fieldsInEditForm = $form;
        $this->fieldsInList = $list;
        $this->fieldsInView =$view;
        $this->fieldsInSearch = $modelName::getAttributes(['is_read','created_at']);
        $this->fieldsInOrder = $modelName::getAttributes();
    }
    public $imgUrl;
    public function newAction(){

        //save image and add image to message
        /*$imgUrl=null;
        $firebase = new Firebase();
        if( !empty($_FILES['image']['name']) && !( $imgUrl=$this->imgUrl= $firebase->saveFile($_FILES['image'] )) ) {
            $this->flash->error($firebase->getValidationMessageText());
            return $this->returnMainForm();
        }
        $this->updateMessageWithImage();*/

        // handle if all clients are selected
        if( $this->request->isPost() && isset( $_POST['customer_id']  ) && $_POST['customer_id'] == 'all'  ){

            // send notification to all
            /*if( !  $this->sendTopic($this->imgUrl))
                return $this->returnMainForm();*/

            // loop on clients  and save data
            $this->redirect = false;
            $clientsList = \Customer::find();
            foreach( $clientsList as $client ){
                $_POST['customer_id'] = $client->id;
                $modelName=$this->modelName;
                $modelObj = new $modelName();
                $Form = new superforms\adminForm($modelObj);
                $Form->addFieldsArray($this->fieldsInCreateForm);
                $Form->bind( $_POST ,$modelObj );
                if( !$modelObj->saveAndCommitFromArray(array_merge_recursive ( $_POST ,  self::GetPostedFiles() )) ){
                    $this->flash->error($modelObj->getMessages());
                    return $this->returnMainForm();
                }
            }
            return $this->response->redirect($this->folderName.'/'. $this->modelName);
        }


        //i send push on save the item
       /* if( $this->request->isPost()){

            if(! ( $client = \client::findFirst($this->request->get('client_id','int',0)) )  ) {
                $this->flash->error('Invalid client_id');
                return $this->returnMainForm();
            }

            if(! $client->google_fcm_id ){
                $this->flash->error('Invalid Device_id for client');
                return $this->returnMainForm();
            }

            $this->device=$client->google_fcm_id;
            $this->send($this->imgUrl);
        }*/

        parent::newAction();
        //$this->removeImageFromMessage();
        //return ;
    }






    function send($imgUrl=null) {
        $firebase=new Firebase();


        if( ! $firebase->send($this->device ,
            ['title'=>$this->request->get('subject'),
                'message'=>$this->request->get('message'),
                'image'=>isset( $imgUrl ) ?$imgUrl :'',
                'payload'=>[
                    'namespace'=>'informative'
                ]
            ]) ) {
            $this->flash->error($firebase->getValidationMessageText());
            return $this->returnMainForm();
        }
        $this->flash->success('Push has been sent');
    }


    function sendTopic($imgUrl=null){
        $firebase=new Firebase();
        if( ! $firebase->sendToTopic($this->request->get('topic') ,
            ['title'=>$this->request->get('subject'),
                'message'=>$this->request->get('message'),
                'image'=>isset( $imgUrl ) ?$imgUrl :'' ,
                'payload'=>[
                    'namespace'=>'informative'
                ] ]) ) {
            $this->flash->error($firebase->getValidationMessageText());
            return $this->returnMainForm();
        }
        $this->flash->success('Push has been sent');
        return true;
    }


    function returnMainForm(){
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
        $this->removeImageFromMessage();
        $this->forms->set('Form', $Form);
    }



    function updateMessageWithImage(){
        if($this->request->isPost()) {
            $_POST['message']= '<p>'.$_POST['message'] .'</p>';
            if(isset( $imgUrl))
                $_POST['message'].='<img style="width:100%" src="'.$this->imgUrl.'"/>';
        }
    }

    function removeImageFromMessage(){
        if($this->request->isPost()) {
            $_POST['message'] =  strip_tags(   $_POST['message'] );
        }
    }


    /**
     * get drivers as json to use in ajaxselect
     * */
    public function getClientsByAjaxAction(){

        $result= \Customer::find(['
            email like "%'.$this->request->get('term').'%" OR
            first_name like "%'.$this->request->get('term').'%" OR
            last_name like "%'.$this->request->get('term').'%"
        ','limit' =>'20']);
        $data=[
            ['field' => 'id',  'key' => 'id'],
            ['field' => 'getFullName',  'key' => 'getFullName'],
        ];
        header('Content-Type: application/json');

        $default=[['getFullName'=>'-- Select --','id'=>''],
                  ['getFullName'=>'-- All --','id'=>'all']];

        $clients = array_merge( $default ,  \ModelBase::getSpecialDataArrayForArray($result,$data) );
        return $this->response->setJsonContent($clients);
    }


    /**
     * get drivers as json to use in ajaxselect
     * */
    public function getTypesAction(){


        header('Content-Type: application/json');

        $default=[['name'=>'type 1','id'=>'1'],
                  ['name'=>'type 2','id'=>'2']];

        return $this->response->setJsonContent($default);
    }


}
