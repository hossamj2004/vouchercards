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
	
	
    /**
     * here we set filters
     */
    public static function getQueryByArray($filters){

        //init conditions
        $params['conditions']=' 1=1 ';
        $params['joinCondition']='  1=1 ';
        $params['join'] ='';
        //main filters
        if($condition = self::conditionFromArray( $filters,
            self::getAttributesAsArray() ,get_called_class() ) )
        $params['conditions'] .= ' and '.$condition ;

        if( isset ( $filters['order'] ) &&  $filters['order']  != '' )  $params['order'] = $filters['order'];
        if( isset ( $filters['limit'] )  &&  $filters['limit']  != ''  ){
            $params['limit'] = $filters['limit'];
            if( isset ( $filters['offset'] ) &&  $filters['offset']  != ''  ) $params['offset']= $filters['offset'];
        }
        
        if(isset($filters['branch_id'])  ){
            $params['join'] .= " left join VoucherBranch
                    on  VoucherBranch.voucher_id = Voucher.id ";
            $params['joinCondition'] .=" and VoucherBranch.branch_id ='".$filters['branch_id']."' ";
        }
        
        return $params;
    }
}