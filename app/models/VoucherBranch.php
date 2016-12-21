<?php

class VoucherBranch extends ModelBase
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
    public $branch_id;
    
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'voucher_branch';
    }
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo("voucher_id", "Voucher", "id");
        $this->belongsTo("branch_id", "Branch", "id");
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


}
