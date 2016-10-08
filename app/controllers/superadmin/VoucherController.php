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


class VoucherController extends AdminBaseController{
    public $modelName ='Voucher';
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
        $list = $modelName::getAttributes(array('description','back_description','package_id','brand_id'));
        $list[]=   ['field' => 'Package->name', 'key' => 'Package'];
        $list[]=   ['field' => 'Brand->name', 'key' => 'Brand'];

        $form=$modelName::getAttributes(array('id','created_at','updated_at','expire_date'));
        $form[] =   ['field' => 'package_id', 'key' => 'Package', 'type' => 'select','selectData' => array(\Package::find(), 'id', 'name')];
        $form[] =   ['field' => 'brand_id', 'key' => 'Package', 'type' => 'select','selectData' => array(\Brand::find(), 'id', 'name')];


        $this->fieldsInCreateForm=$form;
        $this->fieldsInEditForm=$form;


        $view=$this->fieldsInView;
        $view[] =  ['field' => 'description', 'key' => 'description'];
        $view[] =  ['field' => 'back_description', 'key' => 'back_description'];
        $this->fieldsInView=$view;
        $this->fieldsInList=$list;

    }
}
