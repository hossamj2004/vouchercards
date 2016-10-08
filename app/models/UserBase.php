<?php

use Phalcon\Mvc\Model\Validator\Email as Email;

/**
 * Class UserBase
 * this class is base for admin , client and driver
 */
class UserBase extends ModelBase
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
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     * @param $password
     * @return bool
     * this set password to with hmac encoding
     */
    public function setPass($password){
        if(strlen($password) >= 6 )
        {
            if(  isset($this->email) && $this->email!=''){
                $this->password=$password;
                $this->passwordHash();
                return  $this->save();
            }
            $this->appendMessage(new Message("Email is required to save password", "name", "InvalidValue"));
            return false;
        }
        $this->appendMessage(new Message("password must be 6 characters", "name", "InvalidValue"));
        return false;
    }

    /**
     * hash password
     */
    private function passwordHash()
    {
        $this->security = new Phalcon\Security();
        $this->password = $this->security->hash($this->password);
    }

    //--------------------------------------------------------------
    // function to be used for login
    //--------------------------------------------------------------
    /**
     * @param $password
     * @return bool
     *  check password for user if correct return user if wrong return false
     */
    public function isPasswordCorrect($password)
    {
        $security = \Phalcon\Di::getDefault()->getShared('security');
        if(  !$security->checkHash( $password,$this->password ) ){
            $this->setValidationMessage('تسجيل دخول خاطئ , من فضلك تأكد من إدخال بياناتك الصحيحة');
            return false ;
        }
        return true;
    }
}
