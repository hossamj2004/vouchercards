<?php

namespace Phalcon\Forms\Element {



    /**
     * Phalcon\Forms\Element\NestedForm
     * for easy creating forms html as field
     */

    class NestedForm extends \Phalcon\Forms\Element implements \Phalcon\Forms\ElementInterface {
        public  $formData = null ;

        /**
         * Renders the element widget
         *
         * @param array $attributes
         * @return string
         */
        public function render($attributes=null){
            if(!isset($this->formData)){
                $this->formData=new \Phalcon\Forms\Form();
            }
            $html='<div class="panel panel-default"><div class="panel-body">';
            foreach($this->formData->getElements() as $element){
                if( $element->getName() == 'submit' )
                    break;
                if ( get_class( $element ) !='Phalcon\Forms\Element\Hidden')
                    $html.=$element->label();
                $html.=$element->render($attributes);
            }
            $html.='</div></div>';
            return $html;
        }

        /**
         * @return bool
         * set value for inputs from current entity
         */
        public function setValueFromEntity(){
            if( ! $entity= $this->formData->getEntity() )
                return false ;
            foreach($this->formData->getElements() as $element){
                $elementName= $element->getName();

                $elementName =  @array_pop( explode(':', $elementName) );
                if( property_exists  ($entity,$elementName  ) ){
                    $element->setAttribute('value',$entity->$elementName);
                }

                $elementName =  @array_pop( explode('[',   explode(']', $elementName )[0] ) );
                if( property_exists  ($entity,$elementName  ) ){
                    $element->setAttribute('value',$entity->$elementName);
                }
            }
        }
    }
}
