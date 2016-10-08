<?php

class BrandType extends ModelBase
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $type;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'brand_type';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BrandType[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BrandType
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function has_vouchers($package_id){
        //select package brand
        //where package id = $package_id and brand.brand_type_id= this ->id
        return PackageBrand::findFirstByQuery( [
            'package_id'=>$package_id,
            'brand_type_id'=>$this->id ,
        ]  ) ? true : false;
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

        if(isset($filters['customer_id']) || isset($filters['package_id']) ){
            $params['join'] .= " left join Brand
                    on  Brand.brand_type_id = BrandType.id ";
            $params['join'] .= " left join PackageBrand
                    on  PackageBrand.brand_id = Brand.id ";
            if( isset($filters['package_id'])  )
                $params['joinCondition'] .=" and PackageBrand.package_id ='".$filters['package_id']."' ";
            $params['join'] .= " left join CustomerPackage
                    on  PackageBrand.package_id = CustomerPackage.package_id ";
            if( isset($filters['customer_id'])  )
                $params['joinCondition'] .=" and CustomerPackage.customer_id ='".$filters['customer_id']."' ";
        }


        return $params;
    }
}
