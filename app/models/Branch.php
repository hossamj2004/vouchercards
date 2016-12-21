<?php

class Branch  extends ModelBase
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
    public $address;

    /**
     *
     * @var string
     */
    public $longitude;

    /**
     *
     * @var string
     */
    public $latitude;

    /**
     *
     * @var string
     */
    public $hot_line;
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo("brand_id", "Brand", "id");
        $this->hasManyToMany(
            "id",
            "VoucherBranch",
            "branch_id",
            "voucher_id",
            "Voucher",
            "id",
            array('alias' => 'VoucherBranch')
        );
    }

    public function getBrandBranchName(){
        return $this->Brand->name .' : '.$this->name;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'branch';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Branche[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Branche
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
	public function saveFromArray($data){
      if( !parent::saveFromArray($data) ) return false;
       //save client truck type
        if(isset( $data['voucher_branch']['VoucherBranch'] )){
            if( !$this->insertRelatedManyToManyData( $data['voucher_branch']['VoucherBranch'] ,
                'VoucherBranch' ) ){
                return false;
            }
        }
       return true;
	}
}
