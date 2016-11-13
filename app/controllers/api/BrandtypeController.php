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
class BrandtypeController extends apiBaseController {
    public $modelName ='BrandType';
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
        ];
        $this->fieldsInList[]=['field' => 'getFirstImageUrl(default)',
            'key' => 'image'];

    }
}
