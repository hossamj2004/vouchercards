<?php

namespace Phalcon\Forms\Element {



    /**
     * Phalcon\Forms\Element\NestedForm
     * for easy creating forms html as field
     */

    class MultiSelect extends  \Phalcon\Forms\Element\Select {
        public function render($attributes=null){
           return @parent::render($attributes);
        }
    }
}
