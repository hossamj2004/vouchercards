<?php
/**
 * Created by PhpStorm.
 * User: Astm
 * Date: 14/07/16
 *
 * This controller handle Admin menu
 *
 */

namespace app\controllers\superadmin;


class BranchController extends AdminBaseController{
    public $modelName ='Branch';
    public $modelPrimaryKey = 'id';
    public $orderEnabled = true ;
    public $extraButtons=[
        'new'=>true,
        'edit'=>true,
        'delete' =>true,
        'view' =>true,
    ];
    /**
     * @param $dispatcher
     *  Initializing admin main variables that responsible for fields in list , view , search , edit and create pages
     */
    public function beforeExecuteRoute($dispatcher){
        parent::beforeExecuteRoute($dispatcher);
        $this->simpleInit();
        $modelName=$this->modelName;

        $form= $modelName::getAttributes(array("id","created_at","longitude","latitude","brand_id"));
        $form[] =  ['field' => 'brand_id', 'key' => 'Brand id','type' => 'select', 'type' => 'select','selectData' => array(\Brand::find(), 'id', 'name')];
        $form[] =  ['field' => 'longitude', 'key' => '','type' => 'hidden'];
        $form[] =  ['field' => 'latitude', 'key' => '','type' => 'hidden'];
        //make voucher selectable depending on selected brand id using JS
  		$form[] =array('field' => 'voucher_branch[VoucherBranch]', 'key' => 'Vouchers', 'type' => 'manyToMany',
                'selectData' => array(\Voucher::find(), 'id', 'name'),'value'=>'VoucherBranch');
        $form[] =  ['field' => 'location_select', 'key' => 'Location','type' => 'map'];

		
        $list = $modelName::getAttributes(array("description",'brand_id'));
        $list= array_merge($list,array(
            ['field' => 'Brand->name', 'key' => 'Brand'],
        ));

        $view=$list;
        $view[] =  ['field' => 'description', 'key' => 'Description'];

        $this->fieldsInCreateForm=$form;
        $this->fieldsInEditForm=$form;
        $this->fieldsInList=$list;
        $this->fieldsInView=$view;
        
        //fast hack to fix filter vouchers by brand
        echo "
        <script > 
       	$(document).ready(function() { 
			$('#brand_id_drop').change(function(){";
				$brands = \Brand::find();
				echo "$('.multicheck-container input').parent().parent().hide() ;";
				foreach($brands as $brand)
				if($brand->Branch ){
					echo " if ( $(this).val() == '".$brand->id."' ) { ";
					foreach( $brand->Branch as $branch )
						echo " $('.multicheck-container input[value=".$branch->id."]').parent().parent().show(); ";
					echo '}';
				}
				echo "	
			});
			setTimeout(
			  function() 
			  {
			    $('#brand_id_drop').change();
			  }, 500);
			
		});
		</script>";
    }   
    
    public function updateVouchersAction(){
		
		echo '<select id="voucher_branch[VoucherBranch][]_drop" name="voucher_branch[VoucherBranch][]" class="validate[required,minSize[3]]  form-control text-input input_txt" multiple="multiple" style="display: none;">
				<option selected="selected" value="5">brand2</option>
			</select>';
		die();
	}
}
