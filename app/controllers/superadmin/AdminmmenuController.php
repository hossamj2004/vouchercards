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
use app\controllers\front\frontforms\FrontForm;

class AdminMenuController extends AdminBaseController{
    public $modelName ='AdminMenu';
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

        // add empty admin menu item
        $admin_menu=array(['id'=>'0',"name"=>'']);
        $admin_menu=array_merge($admin_menu,\AdminMenu::find()->toArray());

        // Define the forms disabled fields
        $modelName=$this->modelName;
        $form = $modelName::getAttributes(array("id","parent","acl_id"));
        $form= array_merge($form,array(
          ['field' => 'parent', 'key' => 'parent', 'type' => 'select','selectData' => array($admin_menu, 'id', 'name')],

        ));

        // Define the view disabled fields
        $view=[];
        $view = array_merge($view, $modelName::getAttributes(array("acl_id","parent")) );
        $view[] = array('field' => 'AdminMenu->name',  'key' => 'Parent link');

        $search= array_merge( [['field' => 'id', 'key' => 'ID']],
            $form
        );

        // initialize the pages
        $this->fieldsInCreateForm = $form;
        $this->fieldsInEditForm = $form;
        $this->fieldsInSearch = $search;
        $this->fieldsInView = $view;
        $this->fieldsInList = $view;
    }
}
