<?php
class MCMod extends DataObject {
	public static $db = array(
		'Title' => 'Varchar',
		'Description' => 'Text',
	);
	
	public static $has_one = array(
		'CurrentVersion' => 'MCModVersion',
		'Owner' => 'Member',
	);
	
	public static $has_many = array(
		'Versions' => 'MCModVersion',
	);
	
	public static $belongs_many_many = array(
		'Packs' => 'MCPack'
	);
	
	public function getCurrentRelease() {
		if($this->CurrentVersion) return $this->CurrentVersion;
		
		$set = $this->Versions('LiveDate > NOW()');
		if($set->Count()) {
			foreach($set as $version) {
				return $this->CurrentVersion = $version;
			}
		}
	}
}
