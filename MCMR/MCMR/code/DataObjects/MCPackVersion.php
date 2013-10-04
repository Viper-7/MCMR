<?php
class MCPackVersion extends DataObject {
	public static $db = array(
		'Version' => 'Int',
	);
	
	public static $has_one = array(
		'Pack' => 'MCPack',
	);
	
	public static $has_many = array(
		'Mods' => 'MCPackMod',
	);
	
	public static $defaults = array(
		'Version' => 1,
	);
	
	public function getAllModVersions() {
		$mod_list = array();
		
		foreach($this->Mods() as $packmod) {
			$version = $packmod->ModVersion();
			$mod_list[] = $version;
			
			foreach($version->getDependencies($mod_list) as $dependency) {
				if(!in_array($dependency->ModVersion, $mod_list))
					$mod_list[] = $dependency;
			}
		}
		
		return $mod_list;
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		
		foreach($this->getAllModVersions() as $mod) {
			foreach($mod->Config()->Settings() as $setting) {
				/*
				$existing = DataObject::get('MCModConfigSettingValue', 'PackVersionID = ' . intval($this->ID) . ' AND ConfigSettingID = ' . intval($setting->ID));
				
				if(!$existing->Count()) {
					$new = new MCModConfigSettingValue()
					$new->PackVersion = $this
					$new->ConfigSetting = $setting
					$new->Value = $setting->DefaultValue;
					$new->write();
				}
				*/
			}
		}
	}
}
