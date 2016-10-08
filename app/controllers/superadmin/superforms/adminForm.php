<?php
/**
 * GENERAL FILE
 * the base form class that all forms will inhered from it
 * it contain some functions that facilitate using forms like defaultTextField
 *
 */
namespace app\controllers\superadmin\superforms;
use Phalcon\Forms\Element\Text,
	Phalcon\Forms\Element\NestedForm,
	Phalcon\Forms\Element\ImageWithPreview,
	Phalcon\Forms\Element\Password,
	Phalcon\Forms\Element\Submit,
	Phalcon\Forms\Element\Check,
	Phalcon\Forms\Element\Hidden,
	Phalcon\Forms\Element\File,
	Phalcon\Forms\Element\Radio,
	Phalcon\Validation\Validator\PresenceOf,
	Phalcon\Validation\Validator\Email,
	Phalcon\Validation\Validator\Identical,
	Phalcon\Forms\Element\Select,
	Phalcon\Forms\Element\AjaxSelect,
	Phalcon\Forms\Element\Map,
	Phalcon\Forms\Element\MultiSelect;
use Phalcon\Forms\Element\TextArea;
use Recaptcha;
use Phalcon\Security;

class adminForm extends \Phalcon\Forms\Form
{

	public $extraErrorMessage;
	public function initialize(){

	}
	/**
	 * @param $condition
	 * @param $error
	 * @return Hidden
	 *  a fast hack to check a condition on a form submission if failed give error
	 *  used in captcha checking
	 */
	public  function validateConditionField($condition,$error)
	{
		$field= new Hidden(uniqid());
		$field->addValidator(new Identical(array(
			'value' =>$condition? null :uniqid() ,
			'message' => $error
		)));
		return $field;
	}

	/**
	 * @return Hidden
	 * google captcha field
	 */
	function captchaField(){
		$this->view->captcha =  Recaptcha::get('6LdlGwcTAAAAAM9S5mgYD0RAbBu9g1OqJbp3B4Tq');
		return $this->validateConditionField(
			Recaptcha::check(
				'6LdlGwcTAAAAAGoiIFA-crn2wSLprmvJV7qXGZUO',
				(  ($_SERVER['REMOTE_ADDR'] != '::1' )? $_SERVER['REMOTE_ADDR'] :'41.47.167.224' ),
				$this->request->getPost('recaptcha_challenge_field'),
				$this->request->getPost('recaptcha_response_field') )

			,
			'The CAPTCHA was incorrect. Try again.');
	}

	/**
	 * csrf Field ( csrf = Cross-Site Request Forgery )
	 */
	function csrfField(){
		return $this->validateConditionField(
			( isset($_POST['csrf']) &&   $_POST['csrf'] == $this->security->getSessionToken() ) , 'Your submission failed a security check on our system , fill out the form, and try submitting it again.');
	}
	public function getCsrf()
	{
		return $this->security->getToken();
	}
	/**
	 * Simple fields
	 */
	function defaultTextField($name , $label ,$attributes=[], $validations=[]){
		return $this->defaultField('text',$name,$label,$attributes,$validations);
	}
	function defaultTextAreaField($name , $label ,$attributes=[], $validations=[]){
		return $this->defaultField('textArea',$name,$label,$attributes,$validations);
	}
	function defaultPasswordField($name , $label,$attributes=[],  $validations=[]){
		return $this->defaultField('password',$name,$label,$attributes,$validations);
	}
	function defaultHiddenField($name , $label, $attributes=[],$validations=[]){
		return $this->defaultField('hidden',$name,$label,$attributes,$validations);
	}
	function defaultCheckField($name , $label ,$data){
		return $this->defaultField('check',$name,$label,$data);
	}
	function defaultRadioField($name , $label ,$data){
		return $this->defaultField('radio',$name,$label,$data);
	}

	/**
	 * Complicated fields
	 */
	function AddDefaultMultiCheck($name,$Fields,$id,$fname)
	{
		foreach($Fields as $element)
		{
			$this->add($this->defaultCheckField($name.'['.$element->$id.']', $element->$fname,
				array('value'=>$element->$id,
					'name'=>$name.'[]',
					'id'=>$name.'['.$element->$id.']',
					'checked'=> "checked")  ));
		}
	}

	function defaultSelectField($fname , $label, $fields ,$id,$name,$validations=false){
		$resultArr=[];
		foreach($fields as $field)
		{
			if( is_array($field)) $field=(object)$field;
			$resultArr[$field->$id]= method_exists($field,'returnData' ) ? $field->returnData($name,null) : $field->$name ;
		}
		$field = new Select($fname,$resultArr,array('id'=>$fname.'_drop', 'class'=>"select2"));
		$field->setLabel(_($label));
		if( $validations )
			$this->addValidationToField($field,$validations);
		return $field;
	}

	function defaultMapField($fname , $label, $fields,$validations=false){
		$field = new Map($fname);
		if( isset( $fields ))
			$field->mapFields=$fields;
		$field->setLabel(_($label));
		if( $validations )
			$this->addValidationToField($field,$validations);
		return $field;
	}


	function defaultAjaxSelectField($fname , $label, $url ,$id,$name,$defaultData=null,$validations=false){
		$field = new AjaxSelect($fname);
		$field->ajaxUrl =$url;
		$field->ajaxId =$id;
		$field->ajaxName =$name;
		if( isset($defaultData) && $defaultData ){
			$field->defaultId= $defaultData->returnData( $id,null ) ;
			$field->defaultName= $defaultData->returnData($name,null ) ;
		}
		$field->setLabel(_($label));
		if( $validations )
			$this->addValidationToField($field,$validations);
		return $field;
	}


	function defaultMultiSelectField($name , $label, $fields ,$id,$fname,$defaultData=null,$validations=false){
		$resultArr=[];
		$defaults=[];
		foreach($fields as $field)
		{
			if( is_array($field)) $field=(object)$field;
			$resultArr[$field->$id]=$field->$fname;
			if( isset ( $defaultData )){
				$foreignPrimaryKey = 'id';
				foreach( $defaultData as $item ){
					if(  $item->$foreignPrimaryKey == $field->$id )
						$defaults[]=$field->$id;
				}
			}
		}
		$field = new MultiSelect($name,$resultArr,array('id'=>$name.'_drop', 'class'=>"",'multiple'=>'multiple'));
		$field->setDefault($defaults);
		$field->setLabel(_($label));
		if( $validations )
			$this->addValidationToField($field,$validations);
		return $field;
	}


	/**
	 * @param $name
	 * @param $label
	 * @param $fields
	 * @param bool|false $validations
	 * @return NestedForm
	 * generate default nested field reading fields in simple style
	 */
	function defaultNestedField($name , $label, $fields,$defaultObject=null,$validations=false ){
		$fields=self::addPrefix($fields,$name);
		$field = new NestedForm($name);
		$field->formData=new adminForm($defaultObject?$defaultObject:NULL);
		$field->formData->addFieldsArray($fields);
		$field->setLabel(_($label));
		$field->fields=$fields;
		$field->setValueFromEntity();
		return $field;
	}


	//----------------------------------------------------------------------
	// File field
	//----------------------------------------------------------------------
	function defaultFileField($name , $label, $validations = false){
		$textField = new File($name,array('class'=>'input_txt','accept'=>'image/*', 'id'=>$name.'_file' ));
		$textField->setLabel(_($label));
		if( $validations )
			$this->addValidationToField($textField,$validations);
		return $textField;
	}
	function defaultImageField($name , $label,$value=null , $validations = false){
		$textField = new ImageWithPreview($name,array('class'=>'input_txt','accept'=>'image/*', 'id'=>$name.'_file' ));
		if( $value) $textField->imgUrl =$value;
		$textField->setLabel(_($label));
		if( $validations )
			$this->addValidationToField($textField,$validations);
		return $textField;
	}
	public function validationFile($fileName,$types=[],$imgSize=[]){
		$imgTypes=["image/gif", "image/png" ,"image/jpeg" , "image/JPEG" ,"image/PNG", "image/GIF"] ;
		if(in_array('image',$types) )
			$types=array_merge( $types,$imgTypes);

		//check file type
		$f_type=$_FILES[$fileName]['type'];
		if ( !in_array( $f_type, $types ) ){
			$this->extraErrorMessage='Invalid file type';
			return false;
		}

		//check size of image
		if( count($imgSize) >0 ){
			if( ( isset($_FILES[$fileName] ) && $_FILES[$fileName]['tmp_name'] !='' )) {
				if( isset($_FILES[$fileName]) && $_FILES[$fileName]['tmp_name'] !='' ) {
					$file = $_FILES[$fileName]['tmp_name'];
					list($width, $height) = getimagesize($file);
					if($width !=$imgSize['width']  || $height != $imgSize['height'] ) {
						$this->extraErrorMessage=''.$fileName.' size must be '.$imgSize['width'].' x '.$imgSize['height'].' pixels.';
						return false ;
					}
				}else {
					$this->extraErrorMessage=$fileName.' is required';
					return false ;
				}
			}
		}

		return true ;
	}


	/**
	 *   Field object generator for easy creating fields
	 */
	function defaultField($type, $name , $label , $attributes =[], $validations=['required']){
		$attributes=array_merge(['validationClient'=> is_array( $validations ) >0 ? implode(',',$validations) :'',
			'id'=>$name.'_'.$type,
			'placeholder'=>$label] , $attributes) ;
		switch ($type) {
			case 'text':
				$field = new Text($name,$attributes);
				break;
			case 'password':
				$field = new Password($name,$attributes);
				break;
			case 'hidden':
				$field = new Hidden($name, $attributes);
				break;
			case 'radio':
				$field = new Radio($name, $attributes);
				break;
			case 'textArea':
				$field = new TextArea($name, $attributes);
				break;
			default:
				$field = new Text($name,  $attributes);
		}
		$field->setLabel(_($label));
		$this->addValidationToField($field,$validations);
		return $field;
	}

	/**
	 * @param $field
	 * @param $validations : ex ['required' , 'email' ]
	 * to add validation in a more simple way
	 */
	public function addValidationToField($field,$validations){
		if(!is_array($validations) || count($validations) == 0 )
			return;
		return;
	}

	/**
	 * @param $fieldsArr : ex [ field="name" key="name" type="text" ] will add field text
	 * This function responsible for add fields to form from fields array
	 * this done to make it easy to add fields using a clear array
	 */
	public function addFieldsArray($fieldsArr){
		$this->fieldsInForm = $fieldsArr;
		foreach($this->fieldsInForm as $field){
			if(!isset($field['type']))$field['type']='text';
			if(!isset($field['key']))$field['key']='';
			$defaultData=null;
			if($this->getEntity() && isset( $field['value'] ))
				$defaultData=$this->getEntity()->returnData( $field['value'],null ) ;
			switch ($field['type']) {
				case "textArea":
					$this->add($this->defaultTextAreaField($field['field'],$field['key'],['value'=>$defaultData],isset($field['validations']) ?$field['validations'] :['required']) );
					break;
				case "date":
					$this->add($this->defaultTextField($field['field'],$field['key'],['data-type'=>'date','value'=>$defaultData],isset($field['validations']) ?$field['validations'] :['required']));
					break;
				case "dateTime":
					$this->add($this->defaultTextField($field['field'],$field['key'],['data-type'=>'dateTime','value'=>$defaultData] ,isset($field['validations']) ?$field['validations'] :['required'] ));
					break;
				case "int":
					break;
				case "ajaxSelect":
					$this->add($this->defaultAjaxSelectField($field['field'],$field['key'],$field['selectData'][0],$field['selectData'][1],$field['selectData'][2],$defaultData),isset($field['validations']) ?$field['validations'] :null );
					break;
				case "map":
					$this->add($this->defaultMapField($field['field'],$field['key'], isset( $field['mapFields'])?$field['mapFields']:null ,$defaultData),isset($field['validations']) ?$field['validations'] :null );
					break;
				case "select":
					if($field['selectData']){
						$this->add($this->defaultSelectField($field['field'],$field['key'],$field['selectData'][0],$field['selectData'][1],$field['selectData'][2]  ),isset($field['validations']) ?$field['validations'] :null );
					}else{
						$this->add($this->defaultSelectField($field['field'],$field['key'],NULL,NULL,NULL),isset($field['validations']) ?$field['validations'] :null );
					}
					break;
				case "hidden":
					$this->add($this->defaultHiddenField($field['field'], $field['key'] ,['value'=>$defaultData])  ,isset($field['validations']) ?$field['validations'] :null );
					break;
				case "radio":
					$this->AddDefaultMultiRadio($field['field'],$field['radioData'][0],$field['radioData'][1],$field['radioData'][2]);
					break;
				case "file":
					$this->add( $this->defaultFileField($field['field'],$field['key'],isset($field['validations']) ?$field['validations'] :null) );
					break;
				case "image":
					$this->add( $this->defaultImageField($field['field'],$field['key'],$defaultData,isset($field['validations']) ?$field['validations'] :null) );
					break;
				case "password":
					$this->add($this->defaultPasswordField($field['field'],$field['key'],[],isset($field['validations']) ?$field['validations'] :null) );
					break;
				case "text":
					$this->add($this->defaultTextField($field['field'],$field['key'],['value'=>$defaultData],isset($field['validations']) ?$field['validations'] :['required']) );
					break;
				case "manyToMany":
					if($field['selectData']){
						$this->add($this->defaultMultiSelectField($field['field'].'[]',
							$field['key'],
							$field['selectData'][0],
							$field['selectData'][1],
							$field['selectData'][2],
							$defaultData  )
							,isset($field['validations']) ?$field['validations'] :null);
					}else{
						$this->add($this->defaultMultiSelectField($field['field'].'[]',$field['key'],NULL,NULL,NULL),isset($field['validations']) ?$field['validations'] :null );
					}
					break;
				case "nestedForm":
					$defaultData=null;
					if($this->getEntity() && isset( $field['value'] )) $defaultData=$this->getEntity()->returnData( $field['value'],null ) ;
					$this->add($this->defaultNestedField($field['field'],$field['key'],$field['formFields'] , $defaultData) ,isset($field['validations']) ?$field['validations'] :null );
					break;
				default:
					$this->add($this->defaultTextField($field['field'],$field['key'],[],isset($field['validations']) ?$field['validations'] :null) );
			}
		}
		$this->add(new Submit('submit', array(
			'value' => _('submit')
		)));
	}
	/**
	 * function to add to name of the input extra key name => image::name
	 */
	static function addPrefix($fields,$prefix){
		$result=[];
		foreach ( $fields as $field ) {
			$field['field'] =$prefix.'['.$field['field'].']';
			$result[]=$field;
		}
		return $result;
	}

	/**
	 * function to add to name of the input extra key name => image::name
	 */
	static function getPrefixArray($array,$prefix){
		if( isset( $array ) && isset(  $array[$prefix] ) && is_array( $array[$prefix] ) ) return $array[$prefix];
		$result=[];
		foreach ( $array as $key=>$text ) {
			if(0 === strpos($key, $prefix.':'))
				$result[substr($key, strlen($prefix.':')).''] =$text ;
		}
		return $result;
	}

}
