<?php
/**
 * Created by PhpStorm.
 * User: StarWallet
 * Date: 7/4/2016
 * Time: 11:10 AM
 */
namespace app\controllers\api;
use app\interfaces\controllerMethods;
use Phalcon\Mvc\Models;
use Phalcon\Mvc\Controller;
/**
 * Class ApiBogoController
 */
class VoucherController extends apiBaseController {
    public $modelName ='Voucher';
    public $modelPrimaryKey = 'id';
    public $activeApis=[
        'list'=>true,
        'details'=>false,
        'save' =>false,
        'delete' =>false,
    ];
    /**
     * @param $dispatcher
     *  Initializing admin main variables that responsible for fields in list , view , search , edit and create pages
     */
    public function beforeExecuteRoute($dispatcher){
        parent::beforeExecuteRoute($dispatcher);
        $this->simpleInit();
        $this->generalFilter=[
            'package_id'=>$this->apiSystem->client->getPackageID($this->request->get('customer_package_id',null,0)),
            'brand_id'=>$this->request->get('brand_id'),
            'branch_id'=>$this->request->get('branch_id'),
        ];
        $this->fieldsInList[]=['key'=>'quantity_spent','field'=>'getQuantitySpent(customer_package_id)',
            'params'=>['customer_package_id'=>$this->request->get('customer_package_id') ]];
        $this->fieldsInList[]=['key'=>'expire_date','field'=>'getExpireDate'];
        $this->fieldsInList[]=['field' => 'getFirstImageUrl(default)',
            'key' => 'image'];
    }
}
