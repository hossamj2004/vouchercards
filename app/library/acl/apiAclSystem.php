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
class apiAclSystem extends  aclSystem {
    public $customerTypes=[];
    public $roles=[];
    public $errorPage=[];

    /**
     * protection ACL to make sure user has access to each page
     * @return void
     */
    public function RedirectIfNoAccess()
    {
    }
}
