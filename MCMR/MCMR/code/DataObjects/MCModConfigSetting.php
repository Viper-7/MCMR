<?php
class MCModConfigSetting extends DataObject {
	public static $db = array(
		'Section' => 'Varchar',
		'Name' => 'Varchar',
		'Type' => "Enum('String','Int','Double','Boolean')",
		'DefaultValue' => 'Varchar',
		'InGameName' => 'Varchar',
		'IDRange' => 'Integer',
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
}

class MCModConfigBlock extends MCModConfigSetting {

}

class MCModConfigItem extends MCModConfigSetting {

}
