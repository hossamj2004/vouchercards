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


class BranchController extends AdminBaseController{
    public $modelName ='Branch';
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

        $form= $modelName::getAttributes(array("id","created_at","longitude","latitude","brand_id"));
        $form[] =  ['field' => 'brand_id', 'key' => 'Brand id','type' => 'select', 'type' => 'select','selectData' => array(\Brand::find(), 'id', 'name')];
        $form[] =  ['field' => 'longitude', 'key' => '','type' => 'hidden'];
        $form[] =  ['field' => 'latitude', 'key' => '','type' => 'hidden'];
        $form[] =  ['field' => 'location_select', 'key' => 'Location','type' => 'map'];


        $list = $modelName::getAttributes(array("description",'brand_id'));
        $list= array_merge($list,array(
            ['field' => 'Brand->name', 'key' => 'Brand'],
        ));

        $view=$list;
        $view[] =  ['field' => 'description', 'key' => 'Description'];

        $this->fieldsInCreateForm=$form;
        $this->fieldsInEditForm=$form;
        $this->fieldsInList=$list;
        $this->fieldsInView=$view;
    }
}
