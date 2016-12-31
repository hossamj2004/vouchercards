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
class NotificationController extends apiBaseController {
    public $modelName ='Notification';
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
        $this->fieldsInList = $modelName::getAttributes(['message'],true);
        $this->generalFilter=[
			'customer_id'=>$this->apiSystem->client->id,
            'is_read'=>$this->request->get('is_read'),
        ];
    }
    public function detailsAction(){
        if( !$this->activeApis['details'] ){
            $this->error ='Access denied';
            return $this->setJson();
        }
        $modelName = $this->modelName;
        $resultObj = $modelName::findFirstByQuery($modelName::getQueryByArray( array_merge($this->generalFilter, $this->viewFilter) ));
        if(!$resultObj){
            $this->error='invalid filters';
            return $this->setJson();
        }
        $resultObj->is_read = 1;
        $resultObj->save();
        $this->data['item']= $resultObj->getSpecialDataArray($this->fieldsInDetails);
        return $this->setJson();
    }
    public function countAction(){
        $this->data['count_not_read']= \Notification::count('customer_id = '.$this->apiSystem->client->id .' and  is_read = 0 ' ) ;
        return $this->setJson();
    }
}
