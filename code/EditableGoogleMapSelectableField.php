<?php
/**
 * Shows a google map on the front end, allowing the user to move
 * and drag the marker round to select a point then saves this point
 * in a hidden field for the form submission
 *
 */

class EditableGoogleMapSelectableField extends EditableFormField {
	
	static $singular_name = 'Google Map';
	
	static $plural_name = 'Google Maps';
	
	static $api_key = "";
	
	public function Icon()  {
		return 'googlemapselectionfield/images/' . strtolower($this->class) . '.png';	
	}
	
	public function ExtraOptions() {
		$zoomLevels = array();
		for($i = 1; $i < 20; $i++) {
			$message = ($i == 1) ? _t('EditableFormField.LOWEST', 'Lowest') : "";
			$message = ($i == 19) ? _t('EditableFormField.HIGHEST', 'Highest') : $message;
			$zoomLevels[$i] = ($message) ? $i .' - '. $message : $i;
		}
		return new FieldSet(
			new TextField(
				"Fields[$this->ID][CustomSettings][StartLant]", _t('EditableFormField.STARTLATITUDE', 'Starting Point Latitude'), 
				$this->getSetting('StartLant')
			),
			new TextField(
				"Fields[$this->ID][CustomSettings][StartLong]", _t('EditableFormField.STARTLONGITUDE', 'Starting Point Longitude'),
				$this->getSetting('StartLong')
			),
			new DropdownField(
				"Fields[$this->ID][CustomSettings][StartZoom]", _t('EditableFormField.STARTZOOM', 'Starting Zoom Level'),
				$zoomLevels,
				$this->getSetting('StartZoom')
			),
			new TextField(
				"Fields[$this->ID][CustomSettings][MapWidth]", _t('EditableFormField.MAPWIDTH', 'Map Width'),
				$this->getSetting('MapWidth')
			),
			new TextField(
				"Fields[$this->ID][CustomSettings][MapHeight]", _t('EditableFormField.MAPHEIGHT', 'Map Height'),
				$this->getSetting('MapHeight')				
			)
		);
	}
	public function getFormField() {
		$lat = $this->getSetting('StartLant');
		$long = $this->getSetting('StartLong');
		$width = ($this->getSetting('MapWidth')) ? $this->getSetting('MapWidth') : '300px';
		$height = ($this->getSetting('MapHeight')) ? $this->getSetting('MapHeight') : '300px';
		$zoom = ($this->getSetting('StartZoom')) ? $this->getSetting('StartZoom') : '12';
		Requirements::javascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=". self::$api_key ."&sensor=true");
		Requirements::customScript(<<<JS
			$(document).ready(function() {
				
				// default values
				var map = new GMap2(document.getElementById("map"));
				var center = new GLatLng($lat, $long);
				var geocoder = new GClientGeocoder();
				var marker = new GMarker(center, {draggable: true});

				GEvent.addListener(marker, "dragend", function(overlay, point) {
					var point = marker.getLatLng();
					map.setCenter(point);
					geocoder.getLocations(new GLatLng(point.y, point.x), function(response) {
						if(response.Status.code == 200) {
							$("input.googleMapAddressField").val(response.Placemark[0].address);
							$("input[name=$this->Name]").val(response.Placemark[0].address);
						}
					});
				});
				
				map.setCenter(center, $zoom);
				map.addOverlay(marker);
				map.addControl(new GMenuMapTypeControl());
				map.addControl(new GSmallZoomControl3D());
				
				$("input.googleMapAddressField").focus(function() {
					$(this).val("");
				});
				$("input.googleMapAddressSubmit").click(function() {
					var address = $("input.googleMapAddressField").val();
				 	geocoder.getLatLng(
				 		address,
				 		function(point) {
				 			if (!point) {
				 				alert(address + " not found");
				 			} else {
				 				map.setCenter(point,16);
				 				marker.setPoint(point);
				 			}
				 		}
					);
					return false;
				});
			});
JS
);
		return new GoogleMapSelectableField($this->Name, $this->Title);
	}
}
?>