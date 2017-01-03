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


class DashboardController extends SuperAdminBaseController{

    /**
     * @param $dispatcher
     *  Initializing admin main variables that responsible for fields in list , view , search , edit and create pages
     */
    public function indexAction(){
        $statistics=[];
        $statistics=new \stdClass();
        $statistics->packages_count=\Package::count();
        $statistics->vouchers_count=\Voucher::count();
        $statistics->spent_vocuhers_count=\VoucherSpent::count();
        $statistics->Cashier_count=\Cashier::count();
        $statistics->brands_count=\Brand::count();
        $statistics->customers_count=\Customer::count();
        $this->view->dashboard=$statistics;
    }

    public function uploadDefaultImageAction(){

        $fields = [ ];
        $fields[] =  ['field' => 'model', 'key' => 'model', 'type' => 'text'];
        $fields[] =  ['field' => 'class', 'key' => 'class', 'type' => 'text'];
        $fields[] =  ['field' => 'file', 'key' => 'file', 'type' => 'file'];

        //here i will run save array
        $Form = new superforms\adminForm();
        $Form->addFieldsArray($fields);
        $this->forms->set('Form', $Form);

        if($this->request->isPost()) {
            $model = $this->request->get('model');
            $className = $this->request->get('class');
            if (!isset($_FILES['file'])) {
                $this->flash->error('Error upload file');

            }
            $config = \Phalcon\Di::getDefault()->getShared('configuration');
            $name = uniqid();
            $url = \Phalcon\Di::getDefault()->getShared('url');
            $url = $url->getBaseUri();
            if (!file_exists($config->imgPath . "/default_images/" . $className)) {
                mkdir($config->imgPath . "/default_images/" . $className, 0777, true);
            }
            if (!file_exists($config->imgPath . "/default_images/" . $className . '/' . $model)) {
                mkdir($config->imgPath . "/default_images/" . $className . '/' . $model, 0777, true);
            }
            move_uploaded_file($_FILES["file"]["tmp_name"],
                $config->imgPath . "/default_images/" . $className . '/' . $model . '/' . 'default' . '.jpg');
            $this->flash->success('Image uploaded ' .'<img src="'.$url . "img/default_images/" . $className . '/' . $model . '/' . 'default' . '.jpg"' .' height="100px">');
        }

    }

}
