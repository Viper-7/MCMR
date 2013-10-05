<?php
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
		'Versions' => 'MCPackVersion',
	);
	
	public static $many_many = array(
		'Mods' => 'MCMod',
	);
	
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
		
		$version = new MCPackVersion();
		$version->PackID = $this->ID;
		$version->write();
		
		foreach($this->Mods() as $mod) {
			$release = $mod->getLatestVersion();
			
			$version->ModVersions()->add($release);
		}
	}
}
