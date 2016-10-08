<?php
/**
 * Created by PhpStorm.
 * User: Hossam
 * Date: 16/4/15
 *  this file responsible for general functionalities that could be used in super admin controllers like check if user has acces to page
 */

namespace app\controllers\superadmin;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Faker\Factory;
use Phalcon\Exception;
use Phalcon\Filter;
use Phalcon\Mvc\Controller;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
use  Phalcon\Mvc\Url;
use Phalcon\Acl\Adapter;
use Phalcon\Acl;
use Phalcon\Security;
class SuperAdminBaseController extends Controller{
    public  $userType;
    public  $pageName;
    /**
     * this  function run before any request in Super admin
     * it run prepare  system that get session and set data
     * also it set some main tpl vars
     * finally it check if user has access to page
     */
    public function beforeExecuteRoute($dispatcher){
        //prepare system and check user access
        $this->superAdminSystem->prepareSuperAdminSystem();
        $this->superAdminAclSystem->RedirectIfNoAccess() ;
        $this->acl=$this->superAdminAclSystem->getAcl();

        //set some important views variables
        $this->view->setViewsDir($this->configuration->application->superAdminDir);
        $this->view->superAdminSystem = $this->superAdminSystem;
        $this->view->imgUrl =$this->configuration->imgUrl ;
        $this->view->tmpImgUrl =$this->configuration->tmpImgUrl;
        $this->userType = $this->view->superAdminSystem->userType;
        $this->view->acl=$this->superAdminAclSystem->getAcl();
        $this->view->menuItems=\Acl::find();
        $this->view->pageName = $this->pageName;

        $this->view->parentMenuItems= \AdminMenu::find(array(
                            "parent=0",
                            "order" => "ordering"
                        ));

    }

    /**
     * @param $messageId
     *
     */
    public function successRedirect($messageId){
        $this->response->redirect('superadmin/content/success/'.$messageId);
    }

    /**
     * @param $arr
     * @return mixed
     * general function that used to make sure $_POST is not containing any sql injection
     */
    public function sanitizeArray($arr){
        if( !is_array($arr)) {
            $filter = new Filter();
            return $filter->sanitize($arr, "string");
        }
        foreach( $arr as $key=> $item  ) {
            $santiziedArr[$key] = $this->sanitizeArray($item);
        }
        return $santiziedArr;
    }



    //--------------------------------------------------------------
    //Forms realted code
    //--------------------------------------------------------------
    /**
     * @param $arr
     * @param $allowed
     * @return array
     * this function is used to filter array and only get allowed data from it
     * it is user to filter $_POST to make user it have a secure data
     */
    public function filterArr($arr,$allowed){
        return array_intersect_key($arr, array_flip($allowed));
    }


    /**
     * @param $arr
     * @param $allowed
     * @return array
     * same as filterArr but it filter Files object
     */
    public function fileUpload($arr,$allowed,$path){
        $resultArr=[];
        foreach($arr as $element)
        {
            if( in_array(  $element->getKey() , $allowed )  && $element->getSize() >0){
                $uniqueName= uniqid().'.'.pathinfo( $element->getName(), PATHINFO_EXTENSION);
                $resultArr[ $element->getKey() ] = $uniqueName;
                $element->moveTo($path. $uniqueName );
            }
        }
        return $resultArr;
    }

    /**
     * @param $arrFilePathes
     * @param $allowed
     * @return array
     * same as filterArr but it filter Files that uploaded on server using jquery ui file upload
     */
    public function getFilesArrFromServer($arrFilePathes,$allowed,$imgPath){
        $resultArr=[];
        $arr = $this->filterArr($arrFilePathes,$allowed );
        foreach($arr as $key =>$element)
        {
            if( $element !='') {
                $uniqueName = uniqid() . '.' . pathinfo($element, PATHINFO_EXTENSION);
                try {
                    rename($imgPath. $element,
                        $imgPath . $uniqueName);
                    $resultArr[$key] = $uniqueName;
                }catch(Exception $e)
                {
                    return false;
                }
            }
        }
        return $resultArr;
    }
    /**
     * @param $arr
     * @param $allowed
     * @return array
     * same as filterArr but it filter Files object
     */
    public function getFilesArr($arr,$allowed,$imgPath){
        $resultArr=[];
        foreach($arr as $element)
        {
            if(  $element->getSize() && in_array(  $element->getKey() , $allowed ) ){
                $uniqueName= uniqid().'.'.pathinfo( $element->getName(), PATHINFO_EXTENSION);
                $resultArr[ $element->getKey() ] = $uniqueName;
                $element->moveTo($imgPath. $uniqueName );
            }
        }
        return $resultArr;
    }

    public function getClass($object){
        return get_class($object);
    }

}
