<?php

/**
 * A form field which outputs a google map, input field for an address which moves
 * the map.
 *
 * Currently saves the address of a given point. 
 *
 * @todo Save the Long, Lat fields as well
 * @package googlemapselectionfield
 */

class GoogleMapSelectableField extends FormField {
	
	protected $width = "300px";
	
	protected $height = "300px";
	
	/**
	 * Set the width of this field
	 *
	 * @param String Width
	 */
	public function setWidth($width) {
		$this->width = $width;
	}
	
	/**
	 * Set the height of this field
	 *
	 * @param String 
	 */
	public function setHeight($height) {
		$this->height = $height;
	}
	
	function Field() {
		return "
			<div class=\"field text\">
			<label class=\"left\">$this->Title</label>
			<input type=\"text\" id=\"{$this->id()}\" name=\"{$this->name}\" value=\"". _t('EditableFormField.ENTERADDRESS', 'Enter Address') ."\" class=\"text googleMapAddressField\"/>
			<input type=\"submit\" value=\"". _t('EditableFormField.GO', 'Go') ."\" class=\"submit googleMapAddressSubmit\" />
			<div id=\"map_{$this->name}\" style=\"width: $this->width; height: $this->height;\"></div>";
	}
}
?>