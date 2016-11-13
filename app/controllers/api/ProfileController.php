<?php
/**
 * Created by PhpStorm.
 * User: StarWallet
 * Date: 7/4/2016
 * Time: 11:10 AM
 */
namespace app\controllers\api;
use app\interfaces\controllerMethods;
use Phalcon\Mvc\Models;
use Phalcon\Mvc\Controller;
/**
 * Class ApiBogoController
 */
class ProfileController extends apiBaseController {
    public $modelName ='Customer';
    public $modelPrimaryKey = 'id';
    public $activeApis=[
        'list'=>true,
        'details'=>false,
        'save' =>false,
        'delete' =>false,
    ];

    public function editAction(){
		  // Get the request params
		  $data =$this->getArray( [
		    ['key'=>'first_name'  ],
		    ['key'=>'last_name'  ],
		    ['key'=>'email'  ],
		    ['key'=>'birthdate'  ],
		    ['key'=>'mobile'  ],
            ['key'=>'alternative_mobile'  ],
            ['key'=>'DefaultImage' , 'array'=>[
                ['key'=>'image' ],
            ]
            ]]);
        if($this->apiSystem->client->saveAndCommitFromArray($data)){
			$this->data['client']=$this->apiSystem->client->getSpecialDataArray(
                array_merge(\Customer::getAttributes(['password'],true),
                    [
						['field' => 'getFirstImageUrl(default)', 'key' => 'image'],
                    ]
                )
            );
        } else {
          $this->error=$this->apiSystem->client->getValidationMessageText();
        }
        return $this->setJson();
		
	}
    
    
}
