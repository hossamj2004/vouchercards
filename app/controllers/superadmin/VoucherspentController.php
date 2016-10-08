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


class VoucherspentController extends AdminBaseController{
    public $modelName ='VoucherSpent';
    public $modelPrimaryKey = 'id';
    public $orderEnabled = true ;
    public $extraButtons=[
        'new'=>false,
        'edit'=>false,
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
        $list = $modelName::getAttributes(array('voucher_id','customer_id','package_id','cashier_id','branch_id'));

        $list[]=   ['field' => 'Customer->getFullName', 'key' => 'Customer'];
        $list[]=   ['field' => 'CustomerPackage->Package->name', 'key' => 'Package'];
        $list[]=   ['field' => 'Voucher->name', 'key' => 'Voucher'];
        $list[]=   ['field' => 'Branch->Brand->name', 'key' => 'Brand'];
        $list[]=   ['field' => 'Branch->name', 'key' => 'Branch'];
        $list[]=   ['field' => 'Cashier->name', 'key' => 'Cashier'];


        $view=$this->fieldsInView;
        $this->fieldsInView=$view;
        $this->fieldsInList=$list;
    }
}
