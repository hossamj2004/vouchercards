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


class CustomerController extends AdminBaseController{
    public $modelName ='Customer';
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

        // Define the forms disabled fields
        $modelName=$this->modelName;
        $form = $modelName::getAttributes(array("id","created_at","last_login","city_id",'birthdate','password'));
        $form= array_merge($form,array(
            [ 'field' => 'birthdate', 'key' => 'Birthdate' ,'type'=>'date'],
            [ 'field' => 'password', 'key' => 'password' ,'type'=>'password'],
        ));

        // Define the view disabled fieldsBirthdate
        $view=[];
        $view = array_merge($view, $modelName::getAttributes(array("city_id","password")) );

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
