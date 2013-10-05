<?php
class MCModArchive extends File {
	public static $db = array(
		'Resolved' => 'Boolean',
	);
	
	public static $has_one = array(
		'ModVersion' => 'MCModVersion',
		'MCVersion' => 'MCVersion',
	);
	
	public function buildFromFile($path) {
		$storage_path = '/var/www/Uploads/';
		
		$temp = tempnam($storage_path, 'mod');
		
		rename($path, $temp);
		
		if(file_exists($temp)) {
			$this->Filename = $temp;
			$this->write();
		}
		
		$this->MCVersion()->testModArchive($this);
	}
	
	public function copyTo($path) {
		$file = $path . '/' . basename($this->Filename);
		if(!file_exists($file))
			copy($this->Filename, $file);
	}
	
	public function deleteFrom($path) {
		$file = $path . '/' . basename($this->Filename);
		if(file_exists($file))
			unlink($file);
	}
}
