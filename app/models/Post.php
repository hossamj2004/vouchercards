<?php

class Post extends ModelBase
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var 
     */
    public $title;

    /**
     *
     * @var 
     */
    public $content;
    
    /**
     *
     * @var 
     */
    public $created_at='000-00-00 00:00:00';
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'post';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return PackageBrand[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return PackageBrand
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function initialize()
    {
        parent::initialize();
    }


}
