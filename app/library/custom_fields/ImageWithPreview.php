<?php

namespace Phalcon\Forms\Element {



    /**
     * Phalcon\Forms\Element\NestedForm
     * for easy creating forms html as field
     */

    class ImageWithPreview extends \Phalcon\Forms\Element\File {
        public  $formData = null ;
        public $imgUrl=null ;
        /**
         * Renders the element widget
         *
         * @param array $attributes
         * @return string
         */
        public function render($attributes=null){
            if( false && isset ( $this->imgUrl)) {
                $html = '<div class="input-group image-preview" data-original-title="" title="" data-content="<img id=
&quot;dynamic&quot; src=
&quot;' .  $this->imgUrl . '&quot;; style=&quot;max-width: 250px; max-height: 200px;&quot;>" aria-describedby="popover782154">
			<input type="text" class="form-control image-preview-filename" disabled="disabled">
				<span class="input-group-btn">
				<!-- image-preview-clear button -->
				<button type="button" class="btn btn-default image-preview-clear" style="display:none;">
					<span class="glyphicon glyphicon-remove">
					</span> Clear
				</button>
				<!-- image-preview-input -->
				<div class="btn btn-default image-preview-input">
					<span class="glyphicon glyphicon-folder-open">
					</span>
					<span class="image-preview-input-title">
				Browse
					</span>
				' . parent::render($attributes) . '
			    </div>
			</span>
		</div>';
                $html.= "<script>
                $(document).ready(function(){
                $('.image-preview').hover(
                        function () {
                           $('.image-preview').popover('show');
                        },
                         function () {
                           $('.image-preview').popover('hide');
                        }
                    ); });
                </script>";
            }else {
                $html='<div class="input-group image-preview">
			<input type="text" class="form-control image-preview-filename" disabled="disabled">
				<span class="input-group-btn">
				<!-- image-preview-clear button -->
				<button type="button" class="btn btn-default image-preview-clear" style="display:none;">
					<span class="glyphicon glyphicon-remove">
					</span> Clear
				</button>
				<!-- image-preview-input -->
				<div class="btn btn-default image-preview-input">
					<span class="glyphicon glyphicon-folder-open">
					</span>
					<span class="image-preview-input-title">
                Browse
					</span>
					' . parent::render($attributes) . '
			    </div>
			</span>
		</div>';
            }


            return $html;
        }


    }
}
