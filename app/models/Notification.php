<?php

class Notification extends ModelBase
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
    public $notification_type_id;

    /**
     *
     * @var string
     */
    public $notification_receiver_id;

    /**
     *
     * @var $subject
     */
    public $subject;

    /**
     *
     * @var string
     */
    public $template;
    /**
     *
     * @var integer
     */
    public $customer_id;
    
    /**
     *
     * @var integer
     */
    public $type;
    
    /**
     *
     * @var integer
     */
    public $created_at='000-00-00 00:00:00';
    
    /**
     *
     * @var integer
     */
    public $is_read=0;
    /*
      Define the relations
    */
    public function initialize()
    {
        parent::initialize();
        $this->belongsTo("customer_id", "Customer", "id");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'notification';
    }

    /**
     * here we set filters
     */
    public static function getQueryByArray($filters)
    {
        //init conditions
        $params['conditions'] = ' 1=1 ';
        $params['joinCondition'] = ' 1=1 ';
        $params['join'] = '';
        //main filters

        $condition= self::conditionFromArray($filters,
                [
                    'id',
                    //filter by driver
                    'customer_id',
                    //filter by is read
                    'is_read',
                    'type'

                ], 'Notification');
        $params['conditions'] .= $condition ? (' and '. $condition) : '';

        if( isset ( $filters['order'] ))  $params['order'] = $filters['order'];
        if( isset ( $filters['limit'] ))  $params['limit'] = $filters['limit'];
        if( isset ( $filters['offset'] )) $params['offset']= $filters['offset'];
        return $params;
    }
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NotificationWebDriver[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NotificationWebDriver
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
