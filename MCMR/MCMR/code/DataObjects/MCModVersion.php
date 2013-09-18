<?php
class MCModVersion extends DataObject {
	public static $db = array(
		'MajorVersion' => 'Int',
		'MinorVersion' => 'Int',
		'PatchVersion' => 'Int',
		'LiveDate' => 'DateTime',
	);
	
	public static $has_one = array(
		'Config' => 'MCModConfig',
	);
	
	public static $has_many = array(
		'ConfigSets' => 'MCModConfigSet',
		'Archives' => 'MCModArchive',
		'Dependencies' => 'MCModDependency',
	);
	
	public static $belongs_to = array(
		'Mod' => 'MCMod',
		'Dependants' => 'MCModDependency',
	);
	
	public static $belongs_many_many = array(
		'Packs' => 'MCPack'
	);
	
	public static $default_sort = 'MajorVersion DESC, MinorVersion DESC, PatchVersion DESC';
	
	public function Publish() {
		$this->LiveDate = 'now';
		$this->write();
	}

	public function compare(MCModVersion $version) {
		if($version->ModID != $this->ModID) return false;
		
		switch(true) {
			case $this->MajorVersion < $version->MajorVersion:
				return -1;
			case $this->MajorVersion > $version->MajorVersion:
				return 1;
			case $this->MinorVersion < $version->MinorVersion:
				return -1;
			case $this->MinorVersion > $version->MinorVersion:
				return 1;
			case $this->PatchVersion < $version->PatchVersion:
				return -1;
			case $this->PatchVersion > $version->PatchVersion:
				return 1;
			default:
				return 0;
		}
	}
	
	public function getDependencies($dependency_list = array()) {
		$dependency_list = array();
		
		foreach($this->Dependencies as $dependency) {
			if(in_array($dependency->DependsOn, $dependency_list)) continue;
			
			$dependency_list[] = $dependency->DependsOn;
			$dependency_list = array_merge($dependency_list, $dependency->DependsOn->getDependencies($dependency_list));
		}
		
		return $dependency_list;
	}
}
