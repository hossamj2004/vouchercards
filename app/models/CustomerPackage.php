<?php

class CustomerPackage extends ModelBase
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
    public $package_id;

    /**
     *
     * @var integer
     */
    public $customer_id;

    /**
     *
     * @var string
     */
    public $created_at='000-00-00 00:00:00';

    /**
     *
     * @var string
     */
    public $payment_method;

    /**
     *
     * @var integer
     */
    public $quantity;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'customer_package';
    }
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo("package_id", "Package", "id");
        $this->belongsTo("customer_id", "Customer", "id");
    }
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CustomerPackage[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CustomerPackage
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    public static function getQueryByArray($filters){

        //init conditions
        $params['conditions']=' 1=1 ';
        $params['joinCondition']='  1=1 ';
        $params['join'] ='';
        //main filters
        if($condition = self::conditionFromArray($filters,
            self::getAttributesAsArray() ,get_called_class())  )
            $params['conditions'] .= ' and '.$condition ;
        if( isset ( $filters['order'] ) &&  $filters['order']  != '' )  $params['order'] = $filters['order'];
        if( isset ( $filters['limit'] )  &&  $filters['limit']  != ''  ){
            $params['limit'] = $filters['limit'];
            if( isset ( $filters['offset'] ) &&  $filters['offset']  != ''  ) $params['offset']= $filters['offset'];
        }

        if(isset($filters['customer_id'])){
            $params['conditions'] .=" and CustomerPackage.customer_id ='".$filters['customer_id']."' ";
        }

        if(isset($filters['expire_at_less_than'])){
            $params['join'] .= "left join Package
                    on  CustomerPackage.package_id = Package.id";
            $params['joinCondition'] .=" and Package.expire_date > '".$filters['expire_at_less_than']."' ";
        }

        return $params;
    }

}
