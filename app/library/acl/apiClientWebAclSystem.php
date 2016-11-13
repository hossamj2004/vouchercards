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
class apiClientWebAclSystem extends  aclSystem {
    public $customerTypes=[];
    public $roles=[];
    public $errorPage=[];

    /**
     * protection ACL to make sure user has access to each page
     * @return void
     */
    public function ApiErrorIfNoAccess()
    {
        $this->system= \Phalcon\Di::getDefault()->getShared('apiSystem');
        $this->customerTypes= ['client','visitor'];
        $this->roles = [
            ['visitor','authentication','*'],
            ['visitor','password','*'],

			['client','post','*'],
			['client','notification','*'],
 			['client','profile','*'],
            ['client','package','*'],
            ['client','brandtype','*'],
            ['client','brand','*'],
            ['client','branch','*'],
            ['client','voucher','*'],
            ['client','customerpackage','*'],
            ['client','voucherspent','*'],
        ];

        $this->errorMessage = [
            'client' => 'You don\'t have access to this page',
            'visitor' => 'You don\'t have access to this page',
        ];

        return parent::ApiErrorIfNoAccess();
    }
}
