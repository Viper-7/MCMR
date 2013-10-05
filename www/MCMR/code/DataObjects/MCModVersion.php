<?php
class InvalidModException extends Exception {}

class MCModVersion extends DataObject {
	public static $db = array(
		'MajorVersion' => 'Int',
		'MinorVersion' => 'Int',
		'PatchVersion' => 'Int',
	);
	
	public static $has_one = array(
		'Config' => 'MCModConfig',
		'Mod' => 'MCMod',
	);
	
	public static $has_many = array(
		'Archives' => 'MCModArchive',
		'PackMods' => 'MCPackMod',
	);
	
	public static $many_many = array(
		'DependsOn' => 'MCModVersion',
		'ConflictsWith' => 'MCModVersion',
	);
	
	public static $default_sort = 'MajorVersion DESC, MinorVersion DESC, PatchVersion DESC';
	
	public function getVersion() {
		return "{$this->MajorVersion}.{$this->MinorVersion}.{$this->PatchVersion}";
	}
	
	public function getVersionString() {
		$string = $this->getVersion();
		
		switch($this->compare($this->Mod()->CurrentVersion())) {
			case 1:
				$string .= " (beta)";
				break;
			case 0:
				$string .= " (current)";
				break;
		}
		
		return $string;
	}
	
	public function getIsCurrent() {
		$id = $this->Mod()->CurrentVersionID;
		
		return !$id || $this->ID == $id;
	}

	public function compare(MCModVersion $version) {
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
			if(in_array($dependency, $dependency_list)) continue;
			
			$dependency_list[] = $dependency;
			$dependency_list = array_merge($dependency_list, $dependency->getDependencies($dependency_list));
		}
		
		return array_unique($dependency_list);
	}
}
