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
class PostController extends apiBaseController {
    public $modelName ='Post';
    public $modelPrimaryKey = 'id';
    public $activeApis=[
        'list'=>true,
        'details'=>true,
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
        $modelName=$this->modelName;
        $this->fieldsInList = $modelName::getAttributes([],true);
        
        $this->generalFilter=[

        ];
        $this->fieldsInList[]=['field' => 'getFirstImageUrl(default)',
            'key' => 'image'];
        $this->fieldsInDetails[]=['field' => 'getFirstImageUrl(default)',
            'key' => 'image'];
        $this->fieldsInDetails[]=['field' => 'getImagesArray(gallery)',
            'key' => 'gallery'];
    }
}
