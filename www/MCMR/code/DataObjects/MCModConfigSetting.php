<?php
class MCModConfigSetting extends DataObject {
	public static $db = array(
		'Section' => 'Varchar',
		'Name' => 'Varchar',
		'Type' => "Varchar",
		'DefaultValue' => 'Varchar',
		'InGameName' => 'Varchar',
		'IDRange' => 'Int',
	);
	
	public static $has_one = array(
		'Config' => 'MCModConfig',
	);
	
	public static $has_many = array(
		'Values' => 'MCModConfigSettingValue',
	);
	
	public function getExtraIDs($id) {
		if($this->IDRange) {
			return range($id, $id + $this->IDRange);
		}
	}
	
	public function isValid($value) {
		return true;
	}
}

class MCModConfigBlock extends MCModConfigSetting {
	public function isValid($value) {
		return parent::isValid($value) && $value->Value && $value->Value < 4096;
	}
}

class MCModConfigItem extends MCModConfigSetting {
	public function isValid($value) {
		return parent::isValid($value) && $value->Value && $value->Value > 4096;
	}
}
