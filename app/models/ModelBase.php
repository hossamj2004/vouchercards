<?php
/**
 * Created by PhpStorm.
 * User: rudy
 * Date: 2/10/15
 * Time: 1:56 PM
 */
/**
 * @desc list of used namespaces.
 */
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\MetaData\Memory;
use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Mvc\Model\Behavior\SoftDelete;
use Phalcon\Security;
use app\library\responseHandler;
use Phalcon\Mvc\Model\Message as Message;



/**
 * Class ModelBase
 * @desc responsible for sharing methods and variables across the other models of the systems.
 *  share common used functions as:
 * @function filterBuilder()
 * - attributes:-
 * @var $condition
 * @var $parameters
 * @var $dataToBeFiltered
 * @var $conditionCounter
 * @var $security
 */
class ModelBase  extends \Phalcon\Mvc\Model{
    /**
     * @var string
     */
    private $condition;
    /**
     * @var array
     */
    private $parameters;
    /**
     * @var array
     */
    private $dataToBeFiltered;
    /**
     * @var integer
     */
    private $conditionCounter;
    /**
     * @var phalcon security object
     */
    protected $security;




    /**
     * @desc initialize some objects
     */
    public function initialize()
    {

        $this->security = new Security();
        $this->security->setWorkFactor(12);

        $modelMetaData = new Memory();
        $modelAttributes = $modelMetaData->getAttributes($this);

        //enable auto soft deleting
        if( in_array('deleted', $modelAttributes)  ) {
            if( is_null( $this->deleted ) ) $this->deleted = 1;//set default value for deleted
            $this->addBehavior(new SoftDelete(
                array(
                    'field' => 'deleted',
                    'value' => 0
                )
            ));
        }

        //set default for blocked
        if( in_array('blocked', $modelAttributes)  )
            if( is_null( $this->blocked ) ) $this->blocked = 0;

        //enable auto updating createdAt and updatedAt
        if( in_array('created_at', $modelAttributes) ) {
            $this->addBehavior(new Timestampable(array(
                'beforeCreate' => array(
                    'field' => 'created_at',
                    'format' => 'Y-m-d H:i:s',
                )
            )));
        }
        if( in_array('updated_at', $modelAttributes)) {
            $this->addBehavior(new Timestampable(array('beforeUpdate' => array(
                     'field' => 'updated_at',
                     'format' => 'Y-m-d H:i:s'
                 )
            )));
        }

    }
    #region Filters handler
    /**
     * @param $data
     * @param $filters
     * @param int $limit
     * @param int $offset
     * @desc filterBuilder: is responsible for build a dynamic orm conditions binding.
     *  $data: => represent the data to be used as filters.
     *  $filters: => represent the filters in every models use this method.
     *  $limit: => represent the max number of rows to be fetched.
     *  $offset: => represent the number fo page for pagination.
     *  return : => [array for phalcon orm condition and params array].
     * @return array
     */
    protected function filterBuilder($data, $filters,$limit=10,$offset=0)
        {
            $this->condition ='';
            $this->parameters=array();
            $this->dataToBeFiltered = $data;
            $this->conditionCounter =0;
            foreach ($filters as $filter => $specs)
            {
                if(( isset($this->dataToBeFiltered[$filter])&& $this->dataToBeFiltered[$filter]!='' ) || (isset($this->dataToBeFiltered[$filter]) && !empty($specs['force_empty']) && $specs['force_empty']))
                {
                    $this->determineSpecsType($specs,$filter);

                }
            }
          //  $this->softDeleteFetch($data);
            return array($this->condition,'bind'=>$this->parameters,'limit'=>array('number'=>$limit,'offset'=>$offset) );
        }

    /**
     * @param $specs
     * @param $filter
     * @desc determineSpecsType: is responsible for determining the filters sql condition types
     *  $specs: => represent the sql condition types.
     */
    private function determineSpecsType($specs,$filter)
    {
            switch ($specs['type'])
            {
                case 'likeBoth':
                    $this->likeBothCondition($specs['field'],$filter);
                    break;

                case 'likeStart':
                    $this->likeStartCondition($specs['field'],$filter);
                    break;

                case 'likeEnd':
                    $this->likeEndCondition($specs['field'],$filter);
                    break;
                case 'from':
                    $this->fromCondition($specs['field'],$filter);
                    break;
                case 'to':
                    $this->toCondition($specs['field'],$filter);
                    break;
                case 'toOrNull':
                    $this->toOrNullCondition($specs['field'],$filter);
                    break;
                default:
                case 'equal':
                    $this->equalCondition($specs['field'],$filter);
                    break;
            }
        }
        #region Conditions Type Handlers
            /**
             * @param $field
             * @param $filter
             * @desc likeBothCondition: responsible for making likeBoth condition
             */
            private function likeBothCondition($field,$filter){
                if($this->conditionCounter===0){
                    $this->conditionCounter = 1;
                    $this->condition .= " {$field} Like :{$field}: ";
                }else{
                    $this->condition .= " AND {$field} Like :{$field}: ";
                }
                $this->parameters[$field] = '%'.$this->dataToBeFiltered[$filter].'%';
            }
            /**
             * @param $field
             * @param $filter
             * @desc likeStartCondition: responsible for making likeStart condition
             */
            private function likeStartCondition($field,$filter){
                if($this->conditionCounter===0){
                    $this->condition .= " {$field} Like :{$field}: ";
                    $this->conditionCounter = 1;
                }else
                    $this->condition .= " AND {$field} Like :{$field}: ";
                $this->parameters[$field] = $this->dataToBeFiltered[$filter].'%';
            }
            /**
             * @param $field
             * @param $filter
             * @desc likeEndCondition: responsible for making likeEnd condition
             */
            private function likeEndCondition($field,$filter){
                if($this->conditionCounter===0){
                    $this->condition .= " {$field} Like :{$field}: ";
                    $this->conditionCounter = 1;
                }else
                    $this->condition .= " AND {$field} Like :{$field}: ";
                $this->parameters[$field] = '%'.$this->dataToBeFiltered[$filter];
            }
            /**
             * @param $field
             * @param $filter
             * @desc fromCondition: responsible for making from condition
             */
            private function fromCondition($field,$filter){
                if($this->conditionCounter===0){
                    $this->conditionCounter = 1;
                    $this->condition .= " {$field} >= :{$field}: ";
                }else
                    $this->condition .= " AND {$field} >= :{$field}: ";
                $this->parameters[$field] = $this->dataToBeFiltered[$filter];
            }
            /**
             * @param $field
             * @param $filter
             * @desc toCondition: responsible for making to condition
             */
            private function toCondition($field,$filter){
                if($this->conditionCounter===0){
                    $this->condition .= " {$field} <= :{$field}: ";
                    $this->conditionCounter = 1;
                }else
                    $this->condition .= " AND {$field} <= :{$field}: ";
                $this->parameters[$field] = $this->dataToBeFiltered[$filter];
            }

            /**
             * @param $field
             * @param $filter
             * @desc toOrNullCondition: responsible for making to Or Null condition
             */
            private function toOrNullCondition($field,$filter){
                if($this->conditionCounter===0){
                    $this->condition .= " ( {$field} <= :{$field}: OR {$field} IS NULL ) ";
                    $this->conditionCounter = 1;
                }else
                    $this->condition .= " AND  ( {$field} <= :{$field}: OR {$field} IS NULL )  ";
                $this->parameters[$field] = $this->dataToBeFiltered[$filter];
            }


            /**
             * @param $field
             * @param $filter
             * @desc equalCondition: responsible for making equal condition
             */
            private function equalCondition($field,$filter){
                if($this->conditionCounter===0){
                    $this->condition .= " {$field} = :{$field}: ";
                    $this->conditionCounter = 1;
                }
                else
                    $this->condition .= " AND {$field} = :{$field}: ";
                $this->parameters[$field] = $this->dataToBeFiltered[$filter];
            }
        #endregion
    #endregion
    protected function setData($data){
        $modelMetaData = new Memory();
        $modelAttributes = $modelMetaData->getAttributes($this);
        $pk = $this->getPrimaryKeyFieldFromData($modelMetaData->getPrimaryKeyAttributes($this),$data);
        if(isset($data[$pk])){
            $resultModel = $this::findFirst($data[$pk]);
            $resultModelAttributes = $modelMetaData->getAttributes($this);
            foreach($resultModelAttributes as $attribute){
                $this->$attribute = $resultModel->$attribute;
            }
        }
        foreach($modelAttributes as $attribute){
            foreach ($data as $field => $value){
                if($attribute == $field)
                    $this->$attribute = $value;
            }
        }
    }
    private function getPrimaryKeyFieldFromData($primaryKeys,$data){
        $pk = 'id';
        foreach($primaryKeys as $primarykey){
            foreach ($data as $field => $value){
                if($primarykey == $field){
                    $pk = $field;
                    break;
                }
            }
        }
        return $pk;
    }

    /**
     * @param $model
     * @param $getData
     * @param $limit
     * @param $offset
     * @internal param $data
     * @internal param $modelManager
     * @desc getDataAndRelationsFromModel: is responsible to fetch all records related to the current model dynamic.
     * @return array
     */
    protected function getDataAndRelationsFromModel($model,$getData,$limit,$offset){
        $modelManager = \Phalcon\Di::getDefault()->getShared('modelsManager');
        $relations = $modelManager->getRelations(get_class($model));
        $records = $model::find(self::filterBuilder($getData,$model->filters,$limit,$offset));
        $data = array();
        foreach($records as $count=> $record){
            $data[$count] = $record->toArray();
            foreach($relations as $relation){
                $referencedModel = $relation->getReferencedModel();
                $relatedToGet= isset( $getData['relations'] ) ?   $getData['relations']:  array();
                //only get related items that required
                if(  !in_array($referencedModel , $relatedToGet ) &&  !in_array('all' , $relatedToGet )  ) continue;
                $childData = self::getChildData($getData,$relation,$record);
                $referencedModelObject = new $referencedModel();
                $data[$count][$referencedModel] = $referencedModelObject::find(self::filterBuilder($childData,$referencedModelObject->filters,$childData['limit'],$childData['offset']))->toArray();
            }
        }
        return $data;
    }


    /**
     * @author Hossam
     * @desc getChildData: is responsible for getting child data new keys to use it in getDataAndRelationsFromModel
     * example :
     * Brand-relatedToGet[]
     * this will return relatedToGet[] by removing the 'brand-'
     * @return array
     */
    public static function getChildData($getData,$relation,$record)
    {
        $result = array();
        //set filter to get only related items
        $modelName= $relation->getReferencedModel();
        $field = $relation->getFields();
        $referencedField= $relation->getReferencedFields();
        if( isset($field) && isset( $referencedField )   && is_string($referencedField)  ) $result[$referencedField ]=$record->$field;
        //set filters from GET by removing [relatedModelName-]
        foreach($getData as $key=> $attr)
        {
            if (strpos($key, $modelName.'.') !== FALSE)
            {
                $newKey = substr($key, strlen ( $modelName.'.') );
                $result [$newKey] = $getData[$key];
            }
        }
        //make sure we have limit
        $result['limit'] = isset($result['limit']) ? $result['limit']=$result['limit']: 1000;//INF
        $result['offset']= isset($result['offset']) ? $result['offset']:0;
        return $result;
    }


    /**
     * @desc getValidationMessage: is responsible for getting validation messages.
     * @return array
     */
    public function getValidationMessage(){
        $arrMessages = array();
        $count=0;
        foreach ($this->getMessages() as $message) {
            $text=$message->getMessage();
            $arrMessages[$count]['message'] =   $text;
            //$arrMessages[$count]['code']    =   $message->getCode();
            $arrMessages[$count]['field']   =   $message->getField();
            $count++;
        }
        return $arrMessages;
    }

    /**
     * @desc getValidationMessage: is responsible for getting validation messages.
     * @return array
     */
    public function getValidationMessageText(){
        $arrMessages = array();
        $count=0;
        foreach ($this->getMessages() as $message) {
            $arrMessages[] =   $message->getMessage();
            $count++;
        }
        return implode( $arrMessages ,',' );
    }

    /**
     * @param $data
     * @param $offset
     * @param $limit
     * @desc getData: responsible for fetch rows in action table based on filters, offset and limit.
     *  $data:   =>   represent the data used as filters.
     *  $offset: =>   represent the page number for pagination.
     *  $limit:  =>   represent the max number of rows to be fetched.
     *  return:  =>   [array of action objects].
     * @return mixed
     */
    public function getData($data,$offset=0,$limit=1000)
    {
        return self::getDataAndRelationsFromModel($this,$data,$limit,$offset);
    }
    /**
     * @param $data
     * @desc saveData: responsible for store action object in action table.
     *  $data: => represent the object data(information).
     * @return array|bool
     */
    public function saveData($data){

        self::setData($data);
        if($this->save())
            return true;
        $this->returnResponse();
    }
    /**
     * @param $data
     * @desc editData: responsible for update action object in action table.
     *  $data: => represent the object data(information).
     * @return array|bool
     */
    public function editData($data)
    {
        self::setData($data);
        if($this->update())
            return true;
        $this->returnResponse();
    }

    /**
     * @param $data
     * @desc deleteData: responsible for delete campaignTargetHasGender object in campaignTargetHasGender table.
     *  $data: => represent the object data(information).
     * @return array|bool
     */
    public function deleteData($data)
    {
        self::setData($data);
        if($this->delete())
            return true;
        $this->returnResponse();
    }


    /**
     * @param $data
     * @desc check if field or function exist and return its value
     * @return array
     */
    public function getSpecialDataArray($data)
    {

        $result = array();
        foreach($data as $field) {
            $result[ $field['key'] ] =$this->returnData($field['field'],isset($field['params']) ?$field['params'] :[] );
        }
        return $result;
    }

    /**
     * added for easier using for getSpecialDataArray
     */
    public static function getSpecialDataArrayForArray($array,$data){
       $result=[];
        foreach($array as $item){
            //fast hack to enable the function to run over the Row class
            if( get_class( $item ) == 'Phalcon\Mvc\Model\Row' && isset( $item['id'] )){
                $itemClass = get_called_class();
                $item = $itemClass::findFirst($item['id']);
            }
            if( is_array( $item ) ) $item=  self::findFirst($item['id']);
            $result[]=$item->getSpecialDataArray($data);
        }
        return $result;
    }


    public function returnResponse(){
       responseHandler::getResponse($this->getValidationMessage() )->send();
       die();
    }
    /**
     *it delete the related data with type ( has_many )
     * it get all relations of the current type then loop on them and delete
     * BE CARFULL ONLY USE WITH NOT IMPORTANT DATA (i use it with many to many relations)
     */
    public function deleteRelatedData(){
        $modelManager = \Phalcon\Di::getDefault()->getShared('modelsManager');
        $relations = $modelManager->getRelations(get_class( $this ));
        foreach($relations as $relation)
        {
            if($relation->getType() == 2){ //only has many
                $referenceModel = $relation->getReferencedModel();
                $this->$referenceModel->delete();
            }
        }
    }

    /**
     * @param $modelName
     * @param $varName1
     * @param $arrayData1
     * @param $varName2
     * @param $arrayData2
     * @param $transaction
     * @return bool
     * this function save data of
     */
    public function insertSimpleManyToMany($modelName,$varName1,$arrayData1,$varName2,$arrayData2,$transaction){
        if( is_null($arrayData1) || is_null($arrayData1)  ) return true;
        if(!is_array($arrayData1) )$arrayData1=array($arrayData1);
        if(!is_array($arrayData2) )$arrayData2=array($arrayData2);
        foreach($arrayData1 as $element){
            foreach($arrayData2 as $element2) {
                $model = new $modelName();
                $varNameArr1=explode(',',$varName1);
                if ( count( $varNameArr1 ) >1 )
                {
                    foreach($varNameArr1 as $key=> $varName1 )$model->$varName1 = isset( $element[$key]) ? $element[$key] : 0;
                }
                else
                    $model->$varName1 = $element;
                $varNameArr2=explode(',',$varName2);
                if (  count($varNameArr2 ) >1 )
                    foreach($varNameArr2 as $varName2 )$model->$varName2 = isset( $element[$varName2]) ? $element[$varName2] : 0;
                else
                    $model->$varName2 = $element2;
                $model->setTransaction($transaction);
                if( !$model->save()){
                    $model->rollback("Can't save ".$modelName);
                    return false ;
                }
            }
        }
        return true;
    }

    /**
     * @param $objects
     * @param $function
     * @return int
     * this used to get total counts
     */
    public static  function addFunctionResults($objects,$function){
        $result = 0 ;
        foreach($objects as $object)
        {
            $result+= $object->$function();
        }
        return $result;
    }

    /**
     * @param null $params
     * @return mixed
     *search by related data
         $aFilter = array(
            'conditions' => 'name LIKE :search: OR Models\Manufacturer.name LIKE :search:',
            'bind' => array(
                'search' => '%search value%'
            ),
            'order' => 'Models\Manufacturer.name DESC',
            );
         Robot::search($aFilter);
     */
    public static function search($params=null,$count=false)
    {
         $query = self::query();

        // check if we need to join a table
        preg_match_all('/[\S]*\./', $params['conditions'] .' '. ( isset($params['order'] )? $params['order'] :'' )  , $aModelsToJoin);
        if(count($aModelsToJoin) > 0) {
            // remove duplicates
            $aModelsToJoin = array_filter(array_unique($aModelsToJoin));
            foreach ($aModelsToJoin as $model) {
                $query->leftJoin(rtrim($model[0],'.'));
            }
        }

        if(isset($params['conditions'])) {
            $query->where($params['conditions'],isset($params['bind'])?$params['bind']:[]);
        }

        if(isset($params['order'])) {
            $query->order($params['order']);
        }

        if(isset($params['offset'])  && isset($params['limit']) ) {
            $query->limit( isset( $params['limit'] ) ?  $params['limit'] :null ,
                            isset($params['offset']) ?  $params['offset'] :null );
        }
        if($count ){
            return count( $query->execute() );
        }
        return $query->execute();
    }

    /**
     * @param $relatedModel
     * @param $filter
     * @param $primaryKey
     * @param $foreignKey
     * @return mixed
     * This function is used to filter related data
     */
    public function searchRelated($relatedModel,$filter,$primaryKey,$foreignKey,$count=false ){
        $filter['conditions'] = $foreignKey . ' = '.$this->$primaryKey.  ' AND  ( '.$filter['conditions'].' )';
        return $relatedModel::search( $filter ,$count);
    }
    /**
     * @param $array
     */
    public static  function arrayToParams($condition,$bind=[],$order=null,$extra=[]){
        $params = [];
        $params[ 'conditions' ] = $condition;
        $params[ 'bind' ] = $bind;
        $params[ 'order' ] = $order;
        $params=array_merge($params,$extra);
        return $params;
    }

    public function getFieldValue($field){
        return $this-> $field ;
    }
    /**
     * @access protected
     * @static
     * @param array $data Query parameters
     * @Desc fetch deleted data by adding deleted=1 to query parameters using (equalCondition) function
     * @return mixed
     */
    public function checkCondition($primaryKey,$condition)
    {
        return self::findFirst($primaryKey.'='.$this->$primaryKey.' AND ( '.$condition.')' );
    }
    public static  function getAttributes($exclude=[],$useDBKeys=null   )
    {
        $obj = new static();
        //get called class to know if it had field deleted or not
        $modelMetaData = new Memory();
        $modelAttributes = $modelMetaData->getAttributes($obj);
        $arrayData =array_diff($modelAttributes, $exclude );
        $resultArr=[];
        foreach($arrayData as $value){
            $result=[];
            $result['field']= $value;
            $result['key']= $useDBKeys ? $value : ucfirst  ( str_replace( '_',' ', $value ) );
            $resultSelect = $obj->getRelatedDataSelectBox($value);
            if(  isset($resultSelect ))
            {
                $result['selectData']= $resultSelect;
                $result['type']= 'select';
            }
            $resultArr[]=$result;
        }
        return $resultArr;
    }
    public static  function getAttributesAsArray($exclude=[]  )
    {
        $obj = new static();
        //get called class to know if it had field deleted or not
        $modelMetaData = new Memory();
        $modelAttributes = $modelMetaData->getAttributes($obj);
        $arrayData =array_diff($modelAttributes, $exclude );
        $resultArr=[];
        foreach($arrayData as $value){
            $resultArr[]= $value;
        }
        return $resultArr;
    }


    public function getRelatedDataSelectBox($attribute){
        $obj = new static();
        $modelManager = \Phalcon\Di::getDefault()->getShared('modelsManager');
        $modelRelations = $modelManager->getRelations(get_class($obj));
        foreach($modelRelations as $relation){
            if( false /* disable this for performance */ && $attribute == $relation->getFields() &&  ( $relation->getType() == 1 ||   $relation->getType() == 0  )  ){
                $referencedModel = $relation->getReferencedModel();
                $referencedId = $relation->getReferencedFields();
                $modelMetaData = new Memory();
                $referencedModelObje = new $referencedModel();
                $modelAttributes = $modelMetaData->getAttributes($referencedModelObje);
                if( in_array('name', $modelAttributes)  )
                    $text = 'name';
                else
                    $text = $referencedId;
                if( 'is Admin' )
                $relatedDataSelect = [$referencedModel::find(['limit'=>300]),$referencedId,$text];
                return  $relatedDataSelect;
            }
        }
        return null;
    }

    public function setValidationMessage($text=null){
        $text=$text;
        $message = new Message($text);
        $this->appendMessage($message);
    }


    /**
     * @param $condition
     * @param $orderBy
     * @param null $offset
     * @param null $limit
     * @param bool $countOnly
     * @return int|Model\ResultsetInterface
     * function for general filtering
     */
    public static function getListAsObjects($conditions,  $orderBy , $offset = null, $limit = null,$countOnly=false){
        $params = [];
        $params['conditions'] =$conditions;
        $params['order'] = $orderBy;
        $params['offset'] = isset($offset) ? $offset : 0;
        if (isset($limit)) {
            $params['limit'] = $limit;
        }
        if( $countOnly )
            $result = self::count($params['conditions']);
        else
            $result = self::find($params);
        return $result;
    }

    /**
     * @param $filters
     * @return array
     * for easy filtration we can generate condition from array
     */
    public static function conditionFromArray($filters,$onlyAllow=false,$parent=false){
        $conditions=[];
        foreach( $filters as $fieldName => $value  )
        {
            if( ( !$onlyAllow || in_array($fieldName,$onlyAllow) ) && isset($value) ){

                    $condition = ($parent ? ($parent.'.'.$fieldName) :$fieldName)  .
                        (
                        is_array($value) ?
                            ' in ("'. implode('","', $value ).'") '
                                : ' = "' . $value . '" '
                        );

                $conditions[] = $condition;
            }
        else
        unset( $filters[$fieldName] );
        }
        $conditions = join(' AND ', $conditions);
        return   $conditions;
    }

    /**
     * @param $moduleName
     * @param $parameters
     * @param bool $lifeTime
     * @return mixed
     * cache system coppied from bogo
     */
    public static  function checkCache($moduleName,$parameters,$lifeTime = false){
        if($lifeTime != false){
            $frontCache = new Phalcon\Cache\Frontend\Data(array(
                "lifetime" => $lifeTime
            ));

        }else{
            $frontCache = new Phalcon\Cache\Frontend\Data(array(
                "lifetime" => 24*3600
            ));
        }


        $cache = new Phalcon\Cache\Backend\File($frontCache, array(
            "cacheDir" => \Phalcon\Di::getDefault()->getShared('configuration')->application->cacheDir
        ));

        $cacheFileName = $moduleName.'_';
       // unset($parameters['hashOutput']);

        foreach($parameters as $key => $value)
            $cacheFileName .=  $key.'_'.$value.'_';

        $cacheFileName = $moduleName.'_'. md5($cacheFileName);
        $cacheFileName .= '.cache';

        if ($cache->exists($cacheFileName)) {
            $jsonResponse = $cache->get($cacheFileName);
            $result['fileExists'] = true;
            $result['data'] = $jsonResponse;
            return $result;

        }else{
            $result['fileExists'] = false;
            $result['data'] = $cacheFileName;
            return $result;
        }

    }
    public static function createCache($moduleName,$parameters,$data,$lifeTime = false){
        $cacheFileName = $moduleName.'_';
        // unset($parameters['hashOutput']);

        foreach($parameters as $key => $value)
            $cacheFileName .=  $key.'_'.$value.'_';

        $cacheFileName = $moduleName.'_'. md5($cacheFileName);
        $cacheFileName .= '.cache';

        if($lifeTime != false){
            $frontCache = new Phalcon\Cache\Frontend\Data(array(
                "lifetime" => $lifeTime
            ));

        }else{
            $frontCache = new Phalcon\Cache\Frontend\Data(array(
                "lifetime" => 3600
            ));
        }
        $cache = new Phalcon\Cache\Backend\File($frontCache, array(
            "cacheDir" =>  \Phalcon\Di::getDefault()->getShared('configuration')->application->cacheDir
        ));
        $cacheKey = $cacheFileName;
        $robots = $cache->get($cacheKey);
        if ($robots === null) {
            $robots = $data;
            $cache->save($cacheKey, $robots) ;
        }
    }
    public static function clearCache($casheModuleToDelete = false ,$lifeTime = false){


        if($lifeTime != false){
            $frontCache = new Phalcon\Cache\Frontend\Data(array(
                "lifetime" => $lifeTime
            ));

        }else{
            $frontCache = new Phalcon\Cache\Frontend\Data(array(
                "lifetime" => 3600
            ));
        }

        $cache = new Phalcon\Cache\Backend\File($frontCache, array(
            "cacheDir" => \Phalcon\Di::getDefault()->getShared('configuration')->application->cacheDir
        ));


        if($casheModuleToDelete != false){

            $keys = $cache->queryKeys();
            foreach ($keys as $key) {
                if (strpos($key, $casheModuleToDelete) !== FALSE){
                    $cache->delete($key);
                    return true;
                }
            }
        }
        // Delete all items from the cache
        $keys = $cache->queryKeys();
        $undeletedKeys = array();
        foreach ($keys as $key) {
            if(!$cache->delete($key)){
                array_push($undeletedKeys, $key);
            }
        }

        return $undeletedKeys;
    }

    /**
     * @param $params
     * @param bool|false $key
     * @param bool|false $countOnly
     * @return \Phalcon\Mvc\ModelInterface
     * get first record by query
     */
    public static function findFirstByQuery($params,$key=false,$countOnly=false){
        return self::findByQuery($params,$key,$countOnly)->getFirst();
    }

    /**
     * @param $params
     * @param bool|false $key
     * @param bool|false $countOnly
     * @return Model\ResultsetInterface
     * same as (find) but can add queries
     */
    public static function findByQuery($params,$key=false,$countOnly=false){
        //create model
        $nameModel = get_called_class();
        $model = new $nameModel();
        // convert into model
        if( $key )
            $pk=$key;
        else {
            $modelMetaData = new Memory();
            $pk = $modelMetaData->getPrimaryKeyAttributes($model)[0];
        }
        if( $params['joinCondition'] =='')$joinCondition=null;
        //now we generate our params for the join conditions , order,group and joins
        $modelsManager = \Phalcon\Di::getDefault()->getShared('modelsManager');
        $fields=  $nameModel.'.'.$pk . (isset( $params['fields'] ) ? ' , '.$params['fields'] :'' );
        $conditions = isset(  $params['conditions'] )  ?  $params['conditions'] : ' 1=1 ';
        if(isset($params['bind'])) $conditions= self::getConditionFromBind($conditions, $params['bind']);
        $join = isset(  $params['join'] ) ?  $params['join'] : '';
        $joinCondition = isset(  $params['joinCondition'] ) ?('  and ' . $params['joinCondition'] ): ' and 1=1 ';

        $group = isset(  $params['group'] ) ? ' GROUP BY '. $params['group'] : '';
        $order = isset(  $params['order'] ) ? ' ORDER BY '. $params['order'] : '';
        $distinct = isset(  $params['distinct'] ) ? ' distinct '. $params['distinct'] .',' : '';

        $limit = isset(  $params['limit'] ) ?  ' LIMIT '. $params['limit'] : '';
        $offset = isset(  $params['offset'] ) ? ' OFFSET '. $params['offset'] : '';

        //now we run query if count we return only the number of items
        if ($countOnly) {
           if( isset(  $params['group'] ) ) {
               $phql = "SELECT count( distinct  " . $params['group'] . " ) as count_rows FROM " . get_class($model) . " $join where    $conditions  $joinCondition    $order";
           }elseif( isset(  $params['distinct'] ) ){
                $phql = "SELECT count( distinct  ".$params['distinct']." ) as count_rows FROM ".get_class($model)." $join where    $conditions  $joinCondition    $order";
           }else {
               $phql = "SELECT count( distinct  ".$nameModel.".".$pk." ) as count_rows FROM ".get_class($model)." $join where    $conditions $joinCondition    $order";
           }
            $rows = $modelsManager->executeQuery($phql);
            return $rows[0]["count_rows"];
        }

        // else if count = false , we will get the data by executing the query
        echo  $phql = "SELECT $distinct $fields  FROM ".get_class($model)." $join where  $conditions  $joinCondition   $group $order $limit $offset";

        $rows = $modelsManager->executeQuery($phql);

        if( isset( $params[ 'returnAsArray' ]) && $params[ 'returnAsArray' ]){
            return $rows;
        }
         //after executing query we have array ... so we change array to objects by running find on ids found
         $arrIDs=[];
         foreach($rows as $row){
             $arrIDs[]=$row->$pk ;
         }
         if( count($arrIDs) > 0)
             return self::find([ 'conditions'=>$pk.' IN ('.implode(',',$arrIDs).')' ,'order' =>  ' FIELD('.$pk.','.implode(',',$arrIDs).')']);
         else return   self::find([ 'conditions'=>$pk.' IN (0)']) ;
    }

    public static function getConditionFromBind($condition,$bind){
        if(isset( $condition ) && $condition !=''){
            foreach($bind as $key=> $value)
                $condition = str_replace(':'.$key.':','"'.$value.'"',$condition);
            return $condition;
        }
        else return '';
    }


    /**
     * @param bool|false $reference_keys
     * @return string
     * for admin forms for getting images as html structure
     */
    public function getImageHTML( $reference_key=false ){
        $images = $this->getImages($reference_key);
        $image_html='<ul class="images-ul">';
        foreach($images as $image_details){
            $image_html.= $image_details ? '
                <li><img src="'. $image_details->getImageUrl() .'"></li>'
                : '';

        }
        $image_html.='</ul>';
        return $image_html;
    }

    /**
     * @param bool|false $reference_keys
     * @return Image[]
     * get images for current object
     */
    public function getImages($reference_keys=false){
        if(!isset( $this->id )) return false;
        $images = Image::find([
                'factor_id = '.$this->id.' and
                type = "'.get_class($this).'"  '
                . ( $reference_keys ? 'and reference_keys = "'.$reference_keys.'" ':'')
        ]);
        return $images;
    }


    /**
     * @param bool|false $reference_keys
     * @return Image[]
     * get only first image
     */
    public function getFirstImage($reference_keys=false){
        $images = $this->getImages($reference_keys);
        if ( count ( $images ) >0){
            return $images[0];
        }
        return false;
    }
    /**
     *
     * @param bool|false $reference_keys
     * @return Image[]
     * get only first image
     */
    public function getImagesArray($reference_keys=false){
        $images = $this->getImages($reference_keys);
        if( !$images )
            return [];
        return \ModelBase::getSpecialDataArrayForArray($images, [
           [ 'field'=>'id', 'key'=>'id'],
           [ 'field'=>'getImageUrl', 'key'=>'image_url']
        ]);
    }
    /**
     * @param bool|false $reference_keys
     * @return Image[]
     * get only first image
     */
    public function getFirstImageUrl($reference_keys=false){
        $images = $this->getImages($reference_keys);
        if ( count ( $images ) >0){
            if( $images[0] )return $images[0]->getImageUrl();
        }
        return Image::getDefaultImageUrl( get_class($this),  $reference_keys );
    }

    /**
     * functions to facilitate saving data
     */
    public function saveFromArray($data){


        $this->assign($data);
        if(  !$this->save() ){
            if( $this->hasTransaction())
                $this->rollback();
            return false ;
        }
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


    /**
     * delete related data
     */
    public static function deleteByFilter($filter){
        $modelObjects=self::find($filter);
        foreach($modelObjects as $modelObject){
            if( ! $modelObject->delete() )
                return false;
        }
        return true ;
    }


    //-------------------------------------------------------------------------------------
    // this code to make inserting data from array easier
    //-------------------------------------------------------------------------------------

    /**
     * save related data nested data
     * this function read array array data for model and save
     * i could user directly saveArray if i want to save related data but in case i have transaction i need to rollback
     */
    public function insertRelatedDataFromArray($data,$relatedModelName,$modelObject=false){
        $transaction=$this->getTransaction();
        $modelObject= $modelObject ? $modelObject :new $relatedModelName();
        $modelObject->setCurrentTransaction($transaction);
        if( ! $modelObject->saveFromArray($data)){
            $this->setValidationMessage(get_class($modelObject) . ' : ' .$modelObject->getValidationMessageText());
            $this->rollback();
            return false ;
        }
        return $modelObject;
    }
    /**
     * this function to save many to many data
     * it also delete the old before inserting new data
     */
    public function insertRelatedManyToManyData($data,$alias){
        $modelManager = \Phalcon\Di::getDefault()->getShared('modelsManager');
        $relation = $modelManager->getRelationByAlias (get_class( $this ),$alias);
        $this->deleteRelatedManyToManyData($alias);
        if(  $this->insertSimpleManyToMany(
            $relation->getIntermediateModel(),//related many to many model Ex (AdminAcl)
            $relation->getIntermediateReferencedFields(),//refer to the related object id (acl_id)
            $data,//data of related object
            $relation->getIntermediateFields(),//refer to current object id (admin_id)
            $this->id,//id of current object
            $this->getTransaction()
        ) )
            return true ;

        else return false;
    }
    /**
     * @param $alias
     * @return bool
     * delete related data for many to many
     * read alias of relation and delete all data in many to many table for that alias
     */
    public function  deleteRelatedManyToManyData ($alias){
        $modelManager = \Phalcon\Di::getDefault()->getShared('modelsManager');
        $relation = $modelManager->getRelationByAlias ( get_class( $this ),$alias);
        $manyToManyModel=$relation->getIntermediateModel();
        $data = $manyToManyModel::find($relation->getIntermediateFields() .'='.$this->id );
        $transaction=$this->getTransaction();
        foreach($data as $item){
            if( ! $item->delete() )
            {
                $transaction->rollback("Can't delete related data for " . get_class($item ) );
                return false;
            }
        }
        return true ;
    }

    /*
     * helping function to make use of transaction easier
     */
    public function setCurrentTransaction($transaction){
        $this->currentTransaction=$transaction;
        $this->setTransaction($transaction);
        return $this;
    }
    public function getTransaction(){
        if( !isset( $this->currentTransaction ) ){
            $manager = new \Phalcon\Mvc\Model\Transaction\Manager();
            $this->currentTransaction = $manager->get();
            $this->setTransaction($this->currentTransaction );
            $this->currentTransaction;
        }
        return $this->currentTransaction;
    }
    public function rollback(){
        try{
            $this->currentTransaction->rollback();
        }catch (\Exception $e) {
        }
    }
    public function hasTransaction(){
       return isset( $this->currentTransaction );
    }


    /**
     * @param $data
     * @param $fields
     * @return mixed|ModelBase|Model\Resultset
     * this function read field as text and return its data example
     * $this->returnData('City->name') this will return data for city name
     * as i said
     * $this->City->name the reason that it is usable at get data as array
     */
    public function returnData($data,$fields){
        $data=explode('->',$data);
        $movingObject = $this;
        foreach( $data as $block ){

            //prepare variables mainName & variables array
            $block =explode('(',$block);
            $mainName= $block[0];
            // now prepare variables array for method
            if( count( $block ) > 1){
                $block[1] = explode(')',$block[1] )[0];
                $block[1]=explode(',',$block[1]);
                $variablesArray=$block[1];
                if($variablesArray[0]=='')   $variablesArray=[];
            }
            else
                $variablesArray =[];
            foreach ( $variablesArray as  $key => $item ){
                if( isset( $fields[$item])) {
                    $variablesArray[$key] = $fields[$item];
                }
            }

            //see if we have method with mainName
            if (method_exists($movingObject, $mainName) ||  count( $variablesArray) > 0)
            {
                // run method
                $movingObject =
                    @call_user_func_array(array($movingObject, get_class($movingObject).'::'.$mainName), $variablesArray);
                continue;
            }

            //see if we have attribute return it
            if( isset(  $movingObject->$mainName ) ){
                $movingObject =  $movingObject->$mainName;
                continue;
            }



            $movingObject='';
        }
        return $movingObject ;
    }


    //just to reduce code
    public static function getDataArray($filters,$data){
        $jobs =\Job::findByQuery(\Job::getQueryByArray($filters));
        return \ModelBase::getSpecialDataArrayForArray($jobs,$data);
    }

    //function to check has attribute to use in volt
    public function hasProperty($attribute){
        return  property_exists($this ,$attribute );
    }



    /**
     * @param $data
     * @return bool
     * save with transaction
     */
    public function saveAndCommitFromArray($data)
    {
        //work with transaction
        $transaction= $this->getTransaction();

try{
        if( ! $this->saveFromArray($data))
            return false;
}catch(\Exception $e) {

      $this->setValidationMessage($e->getMessage());
return false;
}

        if( !$transaction->commit() )
            return false;

        return true ;
    }


    /**
     * here we set filters
     */
    public static function getQueryByArray($filters){

        //init conditions
        $params['conditions']=' 1=1 ';
        $params['joinCondition']='  1=1 ';
        $params['join'] ='';
        //main filters
        if($condition = self::conditionFromArray( $filters,
            self::getAttributesAsArray() ,get_called_class() ) )
        $params['conditions'] .= ' and '.$condition ;

        if( isset ( $filters['order'] ) &&  $filters['order']  != '' )  $params['order'] = $filters['order'];
        if( isset ( $filters['limit'] )  &&  $filters['limit']  != ''  ){
            $params['limit'] = $filters['limit'];
            if( isset ( $filters['offset'] ) &&  $filters['offset']  != ''  ) $params['offset']= $filters['offset'];
        }

        return $params;
    }
}
