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
class superAdminAclSystem extends  aclSystem {
    public $customerTypes=[];
    public $roles=[];
    public $errorPage=[];

    /**
     * protection ACL to make sure user has access to each page
     * @return void
     */
    public function RedirectIfNoAccess()
    {
        $this->superAdminSystem = \Phalcon\Di::getDefault()->getShared('superAdminSystem');
        $this->router = \Phalcon\Di::getDefault()->getShared('router');
        $this->response = \Phalcon\Di::getDefault()->getShared('response');

        //init my main vars
        $customerTypes= ['admin','visitor'];
        $roles = [
            ['admin','*','*'],
            ['visitor','Authentication','login'],
            ['admin','dashboard','*'],

        ];
        $errorPage = [
            'admin' => $this->superAdminSystem->getHomeLink(),
            'visitor' => $this->superAdminSystem->getHomeLink(),
        ];
        $acl = new \Phalcon\Acl\Adapter\Memory;

        //here i add dynamic roles in case admin logged
        if($this->superAdminSystem->isLogged &&
                $this->superAdminSystem->userType!='admin'  &&
                isset($this->superAdminSystem->admin) &&
                $adminObj=$this->superAdminSystem->admin) {
            $customerTypes[]='admin_'.$adminObj->id;
            $roles=array_merge($roles ,$adminObj->getAclArray());
            $roles[]= ['admin_'.$adminObj->id,'dashboard','*'];
            $errorPage['admin_'.$adminObj->id]=$this->superAdminSystem->getHomeLink();
        }

        //get controller and action
        $controller=$this->router->getControllerName() ;
        $action = $this->router->getActionName() ;

        /**
         * code that do ACL
         * it redirect to page error if not have access
         */

        $acl->setDefaultAction(Acl::DENY);

        foreach($customerTypes as $type )
            $acl->addRole(new \Phalcon\Acl\Role($type));

        if( $this->superAdminSystem->userType!='admin'  && isset($this->superAdminSystem->admin) && $adminObj=$this->superAdminSystem->admin) {

            foreach ($adminObj->getAclArray() as $aclPage) {
                $acl->addResource(new \Phalcon\Acl\Resource($aclPage[1]), ['index']);
                $acl->allow($aclPage[0], $aclPage[1], 'index');
            }
        }

        $acl->addResource(new \Phalcon\Acl\Resource($controller), [$action]);
        foreach($roles as $role ){
            if( ( $role[1] ==$controller ||  $role[1]=='*' ) && ( $role[2] == $action ||  $role[2]=='*' ) ){
                $acl->allow($role[0], $role[1], $role[2]);
            }
        }

        if( in_array($this->superAdminSystem->userType ,$customerTypes ) ){
            if(!($acl->isAllowed($this->superAdminSystem->userType, $controller, $action) == Acl::ALLOW)){
                $this->response->redirect($errorPage[$this->superAdminSystem->userType]);
                $this->response->send();
            }
        }else
        {
            die($this->superAdminSystem->userType .' is invalid user type');
        }
        $this->acl=$acl;
    }
    public function getAcl(){
        return $this->acl;
    }
}
