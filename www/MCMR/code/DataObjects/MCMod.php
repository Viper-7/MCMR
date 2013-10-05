<?php
class MCMod extends DataObject {
	public static $db = array(
		'Title' => 'Varchar',
		'Description' => 'Text',
		'UpVoteCount' => 'Int',
		'DownVoteCount' => 'Int',
	);
	
	public static $has_one = array(
		'CurrentVersion' => 'MCModVersion',
		'Author' => 'Member',
		'ModIcon' => 'Image',
	);
	
	public static $has_many = array(
		'Versions' => 'MCModVersion',
	);
	
	public static $many_many = array(
		'Screenshots' => 'Image',
		'MCVersions' => 'MCVersion',
	);
	
	public static $belongs_many_many = array(
		'Packs' => 'MCPack'
	);
	
	public function PackCount() {
		return $this->Packs()->Count();
	}
	
	public function UpVote() {
		DB::query('UPDATE MCPack SET UpVoteCount = UpVoteCount + 1 WHERE ID = ' . intval($this->ID));
	}
	
	public function DownVote() {
		DB::query('UPDATE MCPack SET DownVoteCount = DownVoteCount + 1 WHERE ID = ' . intval($this->ID));
	}
	
	public function getMCMultiVersion() {
		return $this->MCVersions()->Count() > 1;
	}
	
	public function getMCVersion() {
		$versions = $this->MCVersions()->Map()->toArray();
		return implode(', ', $versions);
	}
	
	public function getLatestVersion() {
		return DataObject::get_one('MCModVersion', 'ModID=' . intval($this->ID), true, 'MajorVersion DESC, MinorVersion DESC, PatchVersion DESC');
	}
	
	public function getModImage() {
		$icon = $this->ModIcon();
		if($icon->ID) return $icon;
		
		return DataObject::get_by_id('Image', 4);
	}
	
}
