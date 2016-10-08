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


class CashierController extends AdminBaseController{
    public $modelName ='Cashier';
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

        $form= $modelName::getAttributes(array("id","created_at","updated_at","branch_id"));
        $form[] =   ['field' => 'brand_type_id', 'key' => 'Branch', 'type' => 'select','selectData' => array(\Branch::find(['order'=>'brand_id']), 'id', 'getBrandBranchName')];

        $list = $modelName::getAttributes(array("branch_id",'password'));
        $list= array_merge($list,array(
            ['field' => 'Branch->Brand->name', 'key' => 'Brand'],
            ['field' => 'Branch->name', 'key' => 'Branch'],
        ));

        $view=$list;

        $this->fieldsInCreateForm=$form;
        $this->fieldsInEditForm=$form;
        $this->fieldsInList=$list;
        $this->fieldsInView=$view;
    }
}
