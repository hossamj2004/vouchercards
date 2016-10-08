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


class BrandtypeController extends AdminBaseController{
    public $modelName ='BrandType';
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

        $form=$this->fieldsInCreateForm;
        $imageForm=array(
            ['field' => 'image', 'key' => 'Image', 'type' => 'file' ,'value'=> 'getImageUrl']
        );
        $form[] =  ['field' => 'DefaultImage', 'key' => '','type' => 'nestedForm',
            'formFields'=>$imageForm,'prefix'=>'DefaultImage','value'=>'getFirstImage(default)'];
        $this->fieldsInCreateForm=$form;
        $this->fieldsInEditForm=$form;


        $view=$this->fieldsInView;
        $view[] =  ['field' => 'getImageHTML(default)', 'key' => 'Image'];
        $this->fieldsInView=$view;
    }
}
