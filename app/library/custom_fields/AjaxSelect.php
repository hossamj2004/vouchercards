<?php

namespace Phalcon\Forms\Element {



    /**
     * Phalcon\Forms\Element\NestedForm
     * for easy creating forms html as field
     */

    class AjaxSelect extends \Phalcon\Forms\Element\text {
        public  $formData = null ;
        public $imgUrl=null ;
        /**
         * Renders the element widget
         *
         * @param array $attributes
         * @return string
         */
        public function render($attributes=null){
			$uniqueID= str_replace(':','_', $this->getName() );
			$uniqueID= str_replace('[','_', $uniqueID );
			$uniqueID= str_replace(']','_', $uniqueID );
			$attributes['class'].=  " selector_$uniqueID ";
                $html = '' . parent::render($attributes) . '
			  ';
                $html.= '<script>
				$(document).ready(function(){
				  $(".selector_'.$uniqueID.'").select2({
						minimumInputLength: 0,
						ajax: {
							url:"'. $this->ajaxUrl .'",
							dataType: "json",
							type: "GET",
							quietMillis: 50,
							data: function (term) {
								return {
									term: term
								};
							},
							results: function (data) {
								return {
									results: $.map(data, function (item) {
										return {
												text: item.'.$this->ajaxName.' ,
												id: item.'.$this->ajaxId.'
											}
										})
									};
								}
							}
						});
						$(".selector_'.$uniqueID.'").data().select2.updateSelection( {
							id: "'.(isset($this->defaultId)? $this->defaultId :'').'",
							text: "'.(isset($this->defaultName)?$this->defaultName:'').'",
						});
						$(".selector_'.$uniqueID.'").val('.(isset($this->defaultId)? $this->defaultId :'').');
					});
                </script>';
            return $html;
        }


    }
}
