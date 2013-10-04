<?php
class MCVersion extends DataObject {
	public static $db = array(
		'Title' => 'Varchar',
	);
}

class MCPack extends DataObject {
	public static $db = array(
		'Title' => 'Varchar',
		'DownloadCount' => 'Int',
		'UpVoteCount' => 'Int',
		'DownVoteCount' => 'Int',
	);
	
	public static $has_one = array(
		'Author' => 'Member',
		'CurrentVersion' => 'MCPackVersion',
		'MCVersion' => 'MCVersion',
		'PackIcon' => 'Image',
	);
	
	public static $has_many = array(
		'Versions' => 'MCPackVersions',
	);
	
	public static $many_many = array(
		'Mods' => 'MCMod',
	);
	
	public function getMCVersion() {
		return array('Title' => '1.6.4');
	}
	
	public function getPackImage() {
		$icon = $this->PackIcon();
		if($icon->ID) return $icon;
		
		return DataObject::get_by_id('Image', 4);
	}
	
	public function trackDownload() {
		DB::query('UPDATE MCPack SET DownloadCount = DownloadCount + 1 WHERE ID = ' . intval($this->ID));
	}
	
	public function UpVote() {
		DB::query('UPDATE MCPack SET UpVoteCount = UpVoteCount + 1 WHERE ID = ' . intval($this->ID));
	}
	
	public function DownVote() {
		DB::query('UPDATE MCPack SET DownVoteCount = DownVoteCount + 1 WHERE ID = ' . intval($this->ID));
	}
	
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
			} catch (Exception $e) {
				$errors[] = $e->getMessage();
			}
			
			$version->ModVersions()->add($release);
		}
		
		if($errors) {
			throw new Exception(implode(', ', $errors));
		}
	}
}
