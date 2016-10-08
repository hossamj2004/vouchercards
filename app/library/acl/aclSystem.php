<?php
/**
 * Description of acl
 *
 * @author hossam
 */
namespace app\aclSystem;
use Phalcon\Acl\Adapter;
use Phalcon\Acl;
use Phalcon\Security;
class aclSystem {
    public $customerTypes=[];
    public $roles=[];
    public $errorPage=[];

    /**
     * protection ACL to make sure user has access to each page
     * @return void
     */
    public function RedirectIfNoAccess()
    {
        $frontSystem= $this->system;
        $router= \Phalcon\Di::getDefault()->getShared('router');
        $response= \Phalcon\Di::getDefault()->getShared('response');

        $customerTypes=$this->customerTypes;
        $roles = $this->roles;
        $errorPage = $this->errorPage;

        $controller=$router->getControllerName() ;
        $action = $router->getActionName() ;
        $acl = new \Phalcon\Acl\Adapter\Memory;
        $acl->setDefaultAction(Acl::DENY);
        foreach($customerTypes as $type )
            $acl->addRole(new \Phalcon\Acl\Role($type));
        $acl->addResource(new \Phalcon\Acl\Resource($controller), [$action]);
        foreach($roles as $role ){
            if( ( $role[1] ==$controller ||  $role[1]=='*' ) && ( $role[2] == $action ||  $role[2]=='*' ) ){
                $acl->allow($role[0], $role[1], $role[2]);
            }
        }
        if( in_array($frontSystem->userType ,$customerTypes) ){
            if(!($acl->isAllowed($frontSystem->userType, $controller, $action) == Acl::ALLOW)){
                $response->redirect($errorPage[$frontSystem->userType]);
                $response->send();
            }
        }else
        {
            die($this->userType .' is invalid user type');
        }

    }


    public function ApiErrorIfNoAccess()
    {
        $frontSystem=  $this->system;
        $router= \Phalcon\Di::getDefault()->getShared('router');
        $response= \Phalcon\Di::getDefault()->getShared('response');

        $customerTypes=$this->customerTypes;
        $roles = $this->roles;
        $controller=$router->getControllerName() ;
        $action = $router->getActionName() ;

        $acl = new \Phalcon\Acl\Adapter\Memory;
        $acl->setDefaultAction(Acl::DENY);
        foreach($customerTypes as $type )
            $acl->addRole(new \Phalcon\Acl\Role($type));
        $acl->addResource(new \Phalcon\Acl\Resource($controller), [$action]);
        foreach($roles as $role ){
            if( ( $role[1] ==$controller ||  $role[1]=='*' ) && ( $role[2] == $action ||  $role[2]=='*' ) ){
                $acl->allow($role[0], $role[1], $role[2]);
            }
        }
        if( in_array($frontSystem->userType ,$customerTypes) ){
            if(!($acl->isAllowed($frontSystem->userType, $controller, $action) == Acl::ALLOW)){
               return $this->errorMessage[$frontSystem->userType];
            }
        }else
        {
            die($this->userType .' is invalid user type');
        }
    }
}
