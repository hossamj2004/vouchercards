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


class PackageController extends AdminBaseController{
    public $modelName ='Package';
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
        $list = $modelName::getAttributes(array('description'));

        $form=$modelName::getAttributes(array('id','created_at','updated_at','expire_date'));
        $imageForm=array(
            ['field' => 'image', 'key' => 'Image', 'type' => 'file' ,'value'=> 'getImageUrl']
        );
        $form[] =  ['field' => 'DefaultImage', 'key' => '','type' => 'nestedForm',
            'formFields'=>$imageForm,'prefix'=>'DefaultImage','value'=>'getFirstImage(default)'];

        $form[] =  ['field' => 'expire_date', 'key' => 'Expire date','type' => 'dateTime'];



        $this->fieldsInCreateForm=$form;
        $this->fieldsInEditForm=$form;


        $view=$this->fieldsInView;
        $view[] =  ['field' => 'getImageHTML(default)', 'key' => 'Image'];
        $this->fieldsInView=$view;
        $this->fieldsInList=$list;

    }
}
