<?php
class MCModConfigSettingValue extends DataObject {
	public static $db = array(
		'Value' => 'Varchar',
	);
	
	public static $belongs_to = array(
		'ConfigSetting' => 'MCModConfigSetting',
	);
	
	public function validate() {
		switch($this->ConfigSetting()->Type) {
			case 'Int':
				return $this->Value == intval($this->Value);
			case 'Double':
				return $this->Value == (double)($this->Value);
				
			default:	// We can't validate strings or booleans
				return true;
		}
	}
}