<?php

class Voucher extends ModelBase
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
    public $brand_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $back_description;

    /**
     *
     * @var integer
     */
    public $status;

    /**
     *
     * @var string
     */
    public $created_at='000-00-00 00:00:00';

    /**
     *
     * @var string
     */
    public $updated_at='000-00-00 00:00:00';

    /**
     *
     * @var integer
     */
    public $package_id;

    /**
     *
     * @var integer
     */
    public $voucher_type_id;

    /**
     *
     * @var integer
     */
    public $quantity;

    public function initialize()
    {
        parent::initialize();
        $this->belongsTo("brand_id", "Brand", "id");
        $this->belongsTo("package_id", "Package", "id");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'voucher';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Voucher[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Voucher
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    public function getQuantitySpent($customer_package_id){
        return VoucherSpent::count('voucher_id = "'.$this->id.'" and  customer_package_id ="'.$customer_package_id.'" ' );
    }
    public function getExpireDate(){
		return $this->Package->expire_date;
	}
}