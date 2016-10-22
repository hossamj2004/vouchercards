<?php

class Cashier extends ModelBase
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
    public $branch_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $password;

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

    public function initialize()
    {
        parent::initialize();
        $this->belongsTo("branch_id", "Branch", "id");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'cashier';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cashier[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Cashier
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
