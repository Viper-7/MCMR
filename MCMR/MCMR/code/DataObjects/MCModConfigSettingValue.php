<?php
class MCModConfigSettingValue extends DataObject {
	public static $db = array(
		'Value' => 'Varchar',
	);
	
	public static $has_one = array(
		'ConfigSetting' => 'MCModConfigSetting',
		'PackMod' => 'MCPackMod',
		'ConfigPack' => 'MCModConfigPack',
	);
	
	public function validate() {
		// Need either a PackMod or ConfigPack attached
		if(!$this->PackModID && !$this->ConfigPackID)
			return false;
		
		$setting = $this->ConfigSetting();
		
		// Run setting specific validation rules
		if(!$setting->isValid($this))
			return false;
		
		// Check data type
		switch($setting->Type) {
			case 'Int':
				return $this->Value == intval($this->Value);
			case 'Double':
				return $this->Value == (double)($this->Value);
				
			default:	// We can't validate strings or booleans
				return true;
		}
	}
}