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
				($this->getSetting('StartLant')) ? $this->getSetting('StartLant') : '10'
			),
			new TextField(
				"Fields[$this->ID][CustomSettings][StartLong]", _t('EditableFormField.STARTLONGITUDE', 'Starting Point Longitude'),
				($this->getSetting('StartLong')) ? $this->getSetting('StartLong') : '10'
			),
			new DropdownField(
				"Fields[$this->ID][CustomSettings][StartZoom]", _t('EditableFormField.STARTZOOM', 'Starting Zoom Level'),
				$zoomLevels,
				($this->getSetting('StartZoom')) ? $this->getSetting('StartZoom') : '1'
			),
			new TextField(
				"Fields[$this->ID][CustomSettings][MapWidth]", _t('EditableFormField.MAPWIDTH', 'Map Width'),
				($this->getSetting('MapWidth')) ? $this->getSetting('MapWidth') : '300'
			),
			new TextField(
				"Fields[$this->ID][CustomSettings][MapHeight]", _t('EditableFormField.MAPHEIGHT', 'Map Height'),
				($this->getSetting('MapHeight')) ? $this->getSetting('MapWidth') : '300'
			)
		);
	}
	public function getFormField() {
		Requirements::javascript("http://maps.google.com/maps?file=api&amp;v=2&amp;key=". self::$api_key ."&sensor=true");
		Requirements::javascriptTemplate("googlemapselectionfield/javascript/GoogleMapSelectionField.js", array(
			'Name' => $this->Name,
			'DefaultLat' => $this->getSetting('StartLant'),
			'DefaultLon' => $this->getSetting('StartLong'),
			'MapWidth' => ($this->getSetting('MapWidth')) ? $this->getSetting('MapWidth') : '300px',
			'MapHeight' => ($this->getSetting('MapHeight')) ? $this->getSetting('MapHeight') : '300px',
			'Zoom' => ($this->getSetting('StartZoom')) ? $this->getSetting('StartZoom') : '12'
		));
		return new GoogleMapSelectableField($this->Name, $this->Title);
	}
}
?>