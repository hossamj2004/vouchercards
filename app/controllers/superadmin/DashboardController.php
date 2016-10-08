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
}
