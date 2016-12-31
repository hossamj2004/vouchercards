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

class ImageController extends AdminBaseController{
    public $modelName ='Image';
    public $modelNameText ='Image';
    public $modelPrimaryKey = 'id';
    public $extraButtons=[
        'new'=>true,
        'edit'=>false,
        'delete' =>true,
        'view' =>true,
        'details' =>false,
    ];
    
  public function beforeExecuteRoute($dispatcher){
      // initialize the model fields
      parent::beforeExecuteRoute($dispatcher);
      $this->simpleInit();

      // Define the disabled fields
      $modelName=$this->modelName;
      $form = $modelName::getAttributes(array("id","image","type","created_at",'reference_keys','factor_id'));
      $form= array_merge($form,array(
      	['field' => 'factor_id', 'key' => '', 'type' => 'hidden'],
      	['field' => 'reference_keys', 'key' => '', 'type' => 'hidden'],
        ['field' => 'image', 'key' => 'Image', 'type' => 'file'],
        ['field' => 'type', 'key' => 'Model name','type' => 'select',
        'selectData' => array([['id'=>'post','name'=>'Post']], 'id', 'name')]
      ));

      // Define the disabled fields in the list view
      $list = $modelName::getAttributes(array("image","created_at"));

      // Define the view
      $view = $modelName::getAttributes(array("image"));
      $view= array_merge($view,array(
        ['field' => 'getImageHTML(profile)', 'key' => 'Image']
      ));

      // Define the search fields
      $search = $modelName::getAttributes(array("id","image","created_at"));

      // initialize the pages
      $this->fieldsInCreateForm = $form;
      $this->fieldsInEditForm=$form;
      $this->fieldsInSearch=[];
      $this->fieldsInView=$view;
      $this->fieldsInList=$list;
      
      //work around hide close filter button 
      echo '<script>$(document).ready(function(){$(".search-close").hide()});</script>';
    }

    // upload new image
    public function newAction(){
      // stop redirect after save the model
      $this->redirect=false;
      // check saving the model
      if( $modelObj= parent::newAction() ){
        // create the main image
        $modelObj->saveFile($_FILES["image"],time(),400,400);
        // redirect for the view
        return $this->response->redirect($this->folderName.'/'. $this->modelName.'/view/'.$modelObj->id.($this->view->current_params?'?'.$this->view->current_params:''));
      }
    }

    // update image
    public function editAction($id){
      // delete the old image
      // $manager->executeQuery("delete from image where id=".$id);
      // ImageSystem::imageUploader($type,$factor_id,$_FILES["image"]);
      // parent::editAction($id);
    }


}
