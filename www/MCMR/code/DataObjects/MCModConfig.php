<?php
class MCModConfigPack extends DataObject {
	public static $db = array(
		'Name' => 'Varchar',
	);
	
	public static $has_one = array(
		'Config' => 'MCModConfig',
		'Author' => 'Member',
		'ModVersion' => 'MCModVersion',
	);
	
	public static $has_many = array(
		'Values' => 'MCModConfigSettingValue',
	);
}

class MCModConfig extends DataObject {
	public static $db = array(
		'Name' => 'Varchar',
		'Title' => 'Varchar',
		'Path' => 'Varchar',
		'Content' => 'Text',
		'HasBlocks' => 'Boolean',
		'HasItems' => 'Boolean',
	);
	
	public static $has_one = array(
		'ModVersion' => 'MCModVersion',
	);
	
	public static $has_many = array(
		'Settings' => 'MCModConfigSetting',
		'ConfigPack' => 'MCModConfigPack',
		'PackMod' => 'MCPackMod',
	);
	
	public function generate($values) {
		$settings = array();
		
		foreach($values as $value) {
			$setting = $value->ConfigSetting();
			$settings[$setting->Section][$setting->Name] = $value->Value;
		}
		
		return MCConfigParser::update($this->Content, $settings);
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		
		$settings = MCConfigParser::parse($this->Content);
		
		foreach($settings as $section => $contents) {
			foreach($contents as $name => $value) {
				$setting = DataObject::get_one('MCModConfigSetting', 
					'Section=\'' . Convert::raw2sql($section) . '\''
					. ' AND Name=\'' . Convert::raw2sql($name) . '\''
				);
				
				if(!$setting) {
					if($section == 'block') {
						$setting = new MCModConfigBlock();
					} elseif($section == 'item') {
						$setting = new MCModConfigItem();
					} else {
						$setting = new MCModConfigSetting();
					}
					
					$setting->Config = $this;
					$setting->Name = $name;
					$setting->Section = $section;
					
					switch($value[0]) {
						case 'S':
							$setting->Type = 'String';
							break;
						case 'I':
							$setting->Type = 'Integer';
							break;
						case 'D':
							$setting->Type = 'Double';
							break;
						case 'B':
							$setting->Type = 'Boolean';
					}
					
					$setting->DefaultValue = $value[1];
					$setting->write();
				}
				
				$this->HasBlocks = $this->HasItems = false;
				
				if($setting instanceof MCModConfigBlock)
					$this->HasBlocks = true;
				
				if($setting instanceof MCModConfigItem)
					$this->HasItems = true;
			}
		}
		
				
		foreach($this->Settings() as $setting) {
			$val = DataObject::get_one('MCModConfigSettingValue', 'ConfigSettingID=' . intval($setting->ID));
			
			$val = new MCModConfigSettingValue();
			$val->Value = $setting->DefaultValue;
			$val->ConfigSetting = $setting;
			$val->write();
		}
	}
}
