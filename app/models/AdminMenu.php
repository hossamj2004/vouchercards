<?php

class AdminMenu extends ModelBase
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
    public $ordering;

    /**
     *
     * @var string
     */
    public $parent=0;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $link;


    /**
     *
     * @var string
     */
    public $acl_id=0;


    /*
      Define the relations
    */
    public function initialize()
    {
      // Define the inner join relation
      $this->hasMany('id', 'AdminMenu', 'parent');
      $this->belongsTo("parent", "AdminMenu", "id");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'admin_menu';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminMenu[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return AdminMenu
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }


    // Return the subLinks
    public function getSubLinks(){
      return \AdminMenu::find(array(
                          "parent = {$this->id}",
                          "order" => "ordering"
                      ));
    }

}
