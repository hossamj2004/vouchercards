<?php

class Package extends ModelBase
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
    public $merchant_id;

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
    public $cost;


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
     * @var string
     */
    public $expire_date;



    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'package';
    }
    public function initialize()
    {
        parent::initialize();
        $this->hasMany("id", "CustomerPackage", "package_id");
         $this->hasManyToMany(
            "id",
            "PackageBrand",
            "package_id",
            "brand_id",
            "Brand",
            "id",
            array('alias' => 'PackageBrand')
        );
    }
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Package[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Package
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
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
        if($condition = self::conditionFromArray($filters,
            self::getAttributesAsArray() ,get_called_class())  )
            $params['conditions'] .= ' and '.$condition ;
        if( isset ( $filters['order'] ) &&  $filters['order']  != '' )  $params['order'] = $filters['order'];
        if( isset ( $filters['limit'] )  &&  $filters['limit']  != ''  ){
            $params['limit'] = $filters['limit'];
            if( isset ( $filters['offset'] ) &&  $filters['offset']  != ''  ) $params['offset']= $filters['offset'];
        }

        if(isset($filters['customer_id'])){
            $params['join'] .= "left join CustomerPackage
                    on  CustomerPackage.package_id = Package.id";
            $params['joinCondition'] .=" and CustomerPackage.customer_id ='".$filters['customer_id']."' ";
        }

        if(isset($filters['expire_at_less_than'])){
            $params['conditions'] .=" and Package.expire_date > '".$filters['expire_at_less_than']."' ";
        }

        return $params;
    }


public function saveFromArray($data){
      if( !parent::saveFromArray($data) ) return false;
       //save client truck type
        if(isset( $data['package_brand']['PackageBrand'] )){
            if( !$this->insertRelatedManyToManyData( $data['package_brand']['PackageBrand'] ,
                'PackageBrand' ) ){
                return false;
            }
        }
       return true;
}

}
