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
class VoucherspentController extends apiBaseController {
    public $modelName ='VoucherSpent';
    public $modelPrimaryKey = 'id';
    public $activeApis=[
        'list'=>false,
        'details'=>false,
        'save' =>true,
        'delete' =>false,
    ];
    /**
     * @param $dispatcher
     *  Initializing admin main variables that responsible for fields in list , view , search , edit and create pages
     */
    public function beforeExecuteRoute($dispatcher){
        parent::beforeExecuteRoute($dispatcher);
        $this->simpleInit();
        $this->dataToSave=[
            'customer_id'=>$this->apiSystem->client->id,
            'cashier_id'=>$this->request->get('cashier_id'),
            'cashier_password'=>$this->request->get('cashier_password'),
            'branch_id'=>$this->request->get('branch_id'),
            'voucher_id'=>$this->request->get('voucher_id'),
            'customer_package_id'=>$this->request->get('customer_package_id'),
        ];
        
    }
     public function saveAction(){
        if( !$this->activeApis['save'] ){
            $this->error ='Access denied';
            return $this->setJson();
        }
        $modelName = $this->modelName;
        if( count( array_merge($this->generalFilter,$this->saveFilter) ) == 0 )
            $resultObj = new $modelName();
        else
            $resultObj = $modelName::findFirst(array_merge($this->generalFilter,$this->saveFilter));

        if( !$resultObj ){
            $this->error='invalid filters';
        }
        
        $arrayToSave= count(  $this->allowedSaveParams )== 0 ? $this->dataToSave :  array_intersect_key($this->dataToSave,array_flip($this->allowedSaveParams));

		$arrayToSave['customer_package_id'] = $this->apiSystem->client->getPackageID($this->request->get('customer_package_id',null,0));

        if( !$resultObj->saveAndCommitFromArray( $arrayToSave ))
        {
            $this->error=$resultObj->getValidationMessageText();
        }
        $this->data['id']= $resultObj->id;
        return $this->setJson();
    }
}
