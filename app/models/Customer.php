<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

class Customer extends UserBase
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
    public $first_name;

    /**
     *
     * @var string
     */
    public $last_name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $mobile;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $alternative_mobile;

    /**
     *
     * @var integer
     */
    public $city_id=0;

    /**
     *
     * @var string
     */
    public $birthdate;

    /**
     *
     * @var string
     */
    public $created_at='000-00-00 00:00:00';

    /**
     *
     * @var string
     */
    public $last_login='000-00-00 00:00:00';

    /*
    Define the relations
    */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $this->validate(
            new Email(
                array(
                    'field'    => 'email',
                    'required' => true,
                )
            )
        );

        if ($this->validationHasFailed() == true) {
            return false;
        }

        return true;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'customer';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Customer[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Customer
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }


    /**
     * @param $data
     * @return bool
     * save data using array
     */
    public function saveFromArray($data)
    {
        //clear password if == '' to avoid password validation
        if ($data['password'] == '') unset($data['password']);


        //set password
        if (isset($data['password'])) {
            $security = \Phalcon\Di::getDefault()->getShared('security');
            if (strlen($data['password']) < 6) {
                $this->setValidationMessage("password must be 6 characters");
                return false;
            }
            $data['password'] = $security->hash(($data['password']));
        }

        //assign data and save
        $this->assign($data);
        if (!$this->save())
            return false;
            
  		//upload image
        $Image= \app\controllers\superadmin\superforms\adminForm::getPrefixArray( $data, 'DefaultImage');
        if( count ($Image ) >0 &&
            isset( $Image ['image'] ) &&
            file_exists($Image ['image'] ['tmp_name']) &&
            is_uploaded_file($Image ['image'] ['tmp_name'])
        ){
            $Image['factor_id'] = $this->id;
            $Image['type']=get_class($this);
            $Image['reference_keys']='default';
            if(  !$this->insertRelatedDataFromArray($Image,
                'Image') )
                return false ;
        }
        return true;
    }


    public function getPackageID($customer_package_id){
        if(! ( $customerPackage = \CustomerPackage::findFirst( 'id = '.$customer_package_id.' and customer_id = "'. $this->id .'" ' ) )){
            return false ;
        }
        return $customerPackage->package_id;
    }

    public function getFullName(){
        return $this->first_name . ' '. $this->last_name;
    }

}
