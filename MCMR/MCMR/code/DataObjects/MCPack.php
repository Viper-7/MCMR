<?php
class MCPack extends DataObject {
	public static $db = array(
		'Title' => 'Varchar',
	);
	
	public static $has_one = array(
		'Author' => 'Member',
		'CurrentVersion' => 'MCPackVersion',
	);
	
	public static $has_many = array(
		'Versions' => 'MCPackVersions',
	);
	
	public static $many_many = array(
		'Mods' => 'MCMod',
	);
	
	public function checkConflicts() {
		$mods = $versions = array();
		
		$currentVersion = $this->findOrMakeCurrentVersion();
	}
	
	public function findOrMakeCurrentVersion() {
		if($this->CurrentVersion) return $this->CurrentVersion;
		
		$errors = array();
		$version = new MCPackVersion();
		$version->Pack = $this;
		foreach($this->Mods as $mod) {
			$release = null;
			
			try {
				$release = $mod->getCurrentRelease();
			catch (Exception $e) {
				$errors[] = $e->getMessage();
			}
			
			$version->ModVersions()->add($release);
		}
		
		if($errors) {
			throw new Exception(implode(', ', $errors));
		}
	}
}
