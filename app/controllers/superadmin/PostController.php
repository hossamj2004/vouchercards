<?php

namespace app\controllers\superadmin;


class PostController extends AdminBaseController{
    public $modelName ='Post';
    public $modelPrimaryKey = 'id';
    public $orderEnabled = true ;
    public $extraButtons=[
        'new'=>true,
        'edit'=>true,
        'delete' =>true,
        'view' =>true,
        'gallery' => [
            'text' => 'Gallery',
            'action' => 'gallery',
            'appearOnCondition' => '',
            'confirmation' => false,
            'class' => ' btn btn-info btn-outline ',
        ] ,
    ];
    /**
     * @param $dispatcher
     *  Initializing admin main variables that responsible for fields in list , view , search , edit and create pages
     */
    public function beforeExecuteRoute($dispatcher){
        parent::beforeExecuteRoute($dispatcher);
        $this->simpleInit();
          // Define the forms disabled fields
        $modelName=$this->modelName;
        $imageForm=array(
            ['field' => 'image', 'key' => 'Image', 'type' => 'file' ,'value'=> 'getImageUrl']
        );
        $form = $modelName::getAttributes(array("id","created_at"));
        $form = array_merge($form,array(
            [ 'field' => 'content', 'key' => 'content' ,'type'=>'textArea'],
        ));
        $form[] =   ['field' => 'DefaultImage', 'key' => '','type' => 'nestedForm',
            'formFields'=>$imageForm,'prefix'=>'DefaultImage','value'=>'getFirstImage(default)'];

        // Define the view disabled fieldsBirthdate
        $view=[
        ];
        $view = array_merge($view, $modelName::getAttributes(array("city_id","password")) );
        $view[] =  ['field' => 'getImageHTML(default)', 'key' => 'Image'];
        $search= array_merge( [['field' => 'id', 'key' => 'ID']],
            $form
        );
        // initialize the pages
        $this->fieldsInCreateForm = $form;
        $this->fieldsInEditForm = $form;
        $this->fieldsInSearch = $search;
        $this->fieldsInList = $view;
        $this->fieldsInView = $view;
	}
	public function galleryAction($id){
	    return $this->response->redirect($this->folderName.'/Image?reference_keys=gallery&factor_id='.$id);
	}
}
?>