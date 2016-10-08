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
class CustomerpackageController extends apiBaseController {
    public $modelName ='CustomerPackage';
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
            'customer_id'=>$this->apiSystem->client->id ,
            'expire_at_less_than'=>date("Y-m-d H:i:s")
        ];
        $data=\Package::getAttributes([],true );
        $data[]= ['field' => 'getFirstImageUrl(profile)',
            'key' => 'image'];
        $this->fieldsInList[]=['field'=>'Package->getSpecialDataArray(data)','key'=>'package','params'=>['data'=>$data]];

    }
}
