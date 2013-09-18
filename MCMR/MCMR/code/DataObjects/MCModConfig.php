<?php
class MCModConfig extends DataObject {
	public static $db = array(
		'Name' => 'Varchar',
		'Title' => 'Varchar',
		'Path' => 'Varchar',
		'Content' => 'Text',
		'HasBlocks' => 'Boolean',
		'HasItems' => 'Boolean',
	);
	
	public static $belongs_to = array(
		'ModVersion' => 'MCModVersion',
		'PackMod' => 'MCPackMod',
	);
	
	public static $has_many = array(
		'Settings' => 'MCModConfigSetting',
	);
	
	public static $extensions = array(
		'Hierarchy',
	);
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		
		if(!$this->Content) {
			if($this->ParentID) {
				$this->Content = $this->Parent()->Content
			}
		}
		
		$settings = MCConfigParser::parse($this->Content);
		
		foreach($settings as $section => $contents) {
			foreach($contents as $name => $value) {
				$setting = DataObject::get_one('MCModConfigSetting', 
					'Section=\'' . Convert::raw2sql($section) . '\''
					. ' AND Name=\'' . Convert::raw2sql($name) . '\''
					. ' AND ConfigID=' . intval($this->ID)
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
			}
		}
	}
}
