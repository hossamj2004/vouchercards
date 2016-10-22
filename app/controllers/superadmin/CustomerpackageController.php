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


class CustomerpackageController extends AdminBaseController{
    public $modelName ='CustomerPackage';
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
        $form = $modelName::getAttributes(array("id","package_id",'customer_id','created_at'));
        $form= array_merge($form,array(
            ['field' => 'package_id', 'key' => 'Package', 'type' => 'select','selectData' => array(\Package::find(), 'id', 'name')],
            ['field' => 'customer_id', 'key' => 'Customer', 'type' => 'select','selectData' => array(\Package::find(), 'id', 'name')],
        ));

        $list = $modelName::getAttributes(array("package_id",'customer_id'));
        $list= array_merge($list,array(
            ['field' => 'Package->name', 'key' => 'Package'],
            ['field' => 'Customer->getFullName', 'key' => 'Customer'],
        ));


        $this->fieldsInCreateForm = $form;
        $this->fieldsInEditForm = $form;
        $this->fieldsInList = $list;

    }
}
