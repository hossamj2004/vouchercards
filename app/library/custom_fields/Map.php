<?php

namespace Phalcon\Forms\Element {



    /**
     * Phalcon\Forms\Element\NestedForm
     * for easy creating forms html as field
     */

    class Map extends \Phalcon\Forms\Element\text {
        public $mapFields  = null ;
        public $imgUrl=null ;
        /**
         * Renders the element widget
         *
         * @param array $attributes
         * @return string
         */
        public function render($attributes=null){
			$configuration= \Phalcon\Di::getDefault()->getShared('configuration');
			$attributes['class'].=  " map-container ";
			$uniqueId=uniqid();
			if( !isset( $this->mapFields )) {
				$this->mapFields=[];
				$this->mapFields['longitude']='longitude';
				$this->mapFields['latitude']='latitude';
				$this->mapFields['address']='address';
			}
			//must be included '<script src="https://maps.googleapis.com/maps/api/js?language=ar&region=EG&key='.$configuration->mapApiKey .'"></script> ';
            $html= '
            		<div id="googleMap'.$uniqueId.'" class="googleMapContainer" style="width:100%;height:600px;"></div>
            		<script>
					var map'.$uniqueId.';
					var marker'.$uniqueId.';
					var infowindow'.$uniqueId.';
					// Define my default address
					var myCenter'.$uniqueId.'=new google.maps.LatLng($("input[name='.$this->mapFields['latitude'].']").val() !="" ?  parseFloat( $("input[name='.$this->mapFields['latitude'].']").val() ) :  30.04413282174578 ,$("input[name='.$this->mapFields['longitude'].']").val() !="" ?  parseFloat(  $("input[name='.$this->mapFields['longitude'].']").val() ) : 31.23636245727539);

					function initialize'.$uniqueId.'(){
					  var mapProp'.$uniqueId.' = {
						center:myCenter'.$uniqueId.',
						zoom:7,
						mapTypeId:google.maps.MapTypeId.ROADMAP
					  };

					  //  define the map
					  map'.$uniqueId.' = new google.maps.Map(document.getElementById("googleMap'.$uniqueId.'"),mapProp'.$uniqueId.');

					  // define the listener
					  google.maps.event.addListener(map'.$uniqueId.', "click", function(event) {
						placeMarker'.$uniqueId.'(event.latLng);
					  });


					  // Add default marker
					  var default_marker'.$uniqueId.' = {
					  	lat: $("input[name='.$this->mapFields['latitude'].']").val() !="" ?  parseFloat(  $("input[name='.$this->mapFields['latitude'].']").val() ) :  30.04413282174578,
					   	lng: $("input[name='.$this->mapFields['longitude'].']").val() !="" ?  parseFloat(  $("input[name='.$this->mapFields['longitude'].']").val() ) : 31.23636245727539,
					   };
					  marker'.$uniqueId.' = new google.maps.Marker({
						position:default_marker'.$uniqueId.',
						map: map'.$uniqueId.',
						draggable: false
					  });
					  marker'.$uniqueId.'.setMap(map'.$uniqueId.');

					  // Add default marker hint
					  infowindow'.$uniqueId.' = new google.maps.InfoWindow();
					  google.maps.event.addListener(marker'.$uniqueId.', "click", function() {
						infowindow'.$uniqueId.'.open(map'.$uniqueId.',marker'.$uniqueId.');
					  });

					  // Add default geocoder
					  addGeocode'.$uniqueId.'();

					} // initialize

					function placeMarker'.$uniqueId.'(location) {
					  if ( marker'.$uniqueId.' ) {
						marker'.$uniqueId.'.setPosition(location);
						infowindow'.$uniqueId.'.setContent("Latitude: " + location.lat() + "<br>Longitude: " + location.lng());
					  }

					  // Add default geocoder
					  addGeocode'.$uniqueId.'();
					} // placeMarker

					function addGeocode'.$uniqueId.'(){
					  var geocoder'.$uniqueId.' = new google.maps.Geocoder();
					  geocoder'.$uniqueId.'.geocode({"latLng": marker'.$uniqueId.'.getPosition()}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
						  if (results[0]) {
							//$("input[name='.$this->mapFields['address'].']").val(results[0].formatted_address);
							$("input[name='.$this->mapFields['latitude'].']").val(marker'.$uniqueId.'.getPosition().lat());
							$("input[name='.$this->mapFields['longitude'].']").val(marker'.$uniqueId.'.getPosition().lng());

							infowindow'.$uniqueId.'.setContent("Latitude: " + marker'.$uniqueId.'.getPosition().lat() + "<br>Longitude: " + marker'.$uniqueId.'.getPosition().lng() + "<br>Address:" + results[0].formatted_address);
							infowindow'.$uniqueId.'.open(map'.$uniqueId.', marker'.$uniqueId.');
						  }
						}
					});
					}; //addGeocode


					google.maps.event.addDomListener(window, "load", initialize'.$uniqueId.');
					</script>
					';
            return $html;
        }


    }
}
