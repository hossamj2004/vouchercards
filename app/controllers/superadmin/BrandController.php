<?php
/**
 * Created by PhpStorm.
 * User: Astm
 * Date: 14/07/16
 *
 * This controller handle Admin menu
 *
 */

namespace app\controllers\superadmin;


class BrandController extends AdminBaseController{
    public $modelName ='Brand';
    public $modelPrimaryKey = 'id';
    public $orderEnabled = true ;
    public $extraButtons=[
        'new'=>true,
        'edit'=>true,
        'delete' =>true,
        'view' =>true,
    ];
    /**
     * @param $dispatcher
     *  Initializing admin main variables that responsible for fields in list , view , search , edit and create pages
     */
    public function beforeExecuteRoute($dispatcher){
        parent::beforeExecuteRoute($dispatcher);
        $this->simpleInit();
        $modelName=$this->modelName;

        $form= $modelName::getAttributes(array("id","created_at","updated_at","brand_type_id"));
        $imageForm=array(
            ['field' => 'image', 'key' => 'Image', 'type' => 'file' ,'value'=> 'getImageUrl']
        );
        $form[] =  ['field' => 'DefaultImage', 'key' => '','type' => 'nestedForm',
            'formFields'=>$imageForm,'prefix'=>'DefaultImage','value'=>'getFirstImage(default)'];
        $form[] =   ['field' => 'brand_type_id', 'key' => 'Brand Type', 'type' => 'select','selectData' => array(\BrandType::find(), 'id', 'type')];


        $list = $modelName::getAttributes(array("description",'brand_type_id'));
        $list= array_merge($list,array(
            ['field' => 'BrandType->type', 'key' => 'Brand Type'],
        ));

        $view=$list;
        $view[] =  ['field' => 'description', 'key' => 'Description'];
        $view[] =  ['field' => 'getImageHTML(default)', 'key' => 'Image'];


        $this->fieldsInCreateForm=$form;
        $this->fieldsInEditForm=$form;
        $this->fieldsInList=$list;
        $this->fieldsInView=$view;
    }
}
