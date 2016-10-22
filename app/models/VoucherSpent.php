<?php

class VoucherSpent extends ModelBase
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $voucher_id;

    /**
     *
     * @var integer
     */
    public $customer_id;

    /**
     *
     * @var integer
     */
    public $branch_id;

    /**
     *
     * @var integer
     */
    public $customer_package_id;

    /**
     *
     * @var integer
     */
    public $cashier_id;

    /**
     *
     * @var string
     */
    public $created_at='000-00-00 00:00:00';

    public function initialize()
    {
        parent::initialize();
        $this->belongsTo("voucher_id", "Voucher", "id");
        $this->belongsTo("branch_id", "Branch", "id");
        $this->belongsTo("cashier_id", "Cashier", "id");
        $this->belongsTo("customer_id", "Customer", "id");
        $this->belongsTo("customer_package_id", "CustomerPackage", "id");
    }
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'voucher_spent';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return VoucherSpent[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return VoucherSpent
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function saveFromArray($data){
       //first make sure user own the voucher
        if(!isset( $data['voucher_id'] )){
            $this->setValidationMessage('voucher_id is required');
            return false;
        }

        if( ! ( $voucher= \Voucher::findFirst($data['voucher_id']) ) ){
            $this->setValidationMessage('voucher_id is invalid');
            return false;
        }

        if( ! ($customerPackage =  \CustomerPackage::findFirst( 'id = '.$data['customer_package_id'].' and  package_id = "'.$voucher->package_id.'" and customer_id = "'.$data['customer_id'].'" ' )  )){
            $this->setValidationMessage('invalid customer package');
            return false;
        }

        if(!( $branch  = \Branch::findFirst($data['branch_id']) ) ){
            $this->setValidationMessage('invalid branch_id');
            return false;
        }

        if( $branch->brand_id !=  $voucher->brand_id ){
            $this->setValidationMessage(' branch_id is related to another brand ');
            return false;
        }

        if(  !( $cashier =\Cashier::findFirst( 'password = "'. $data['cashier_password'].'" and branch_id = "'. $data['branch_id'] .'" ' ) ) ){
            $this->setValidationMessage('invalid cashier password');
            return false;
        }
        $data['cashier_id'] = $cashier->id;

        if( $voucher->getQuantitySpent($customerPackage->id)  >= $voucher->quantity ){
            $this->setValidationMessage('All vouchers spent');
            return false;
        }

        return parent::save($data);

    }
}
