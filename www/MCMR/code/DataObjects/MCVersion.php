<?php
class MCVersion extends DataObject {
	public static $db = array(
		'Title' => 'Varchar',
		'ServerJarPath' => 'Varchar',
		'ItemHash' => 'Text',
		'BlockHash' => 'Text',
	);
	
	public static $belongs_many_many = array(
		'Mods' => 'MCMod',
	);
	
	public function testModArchive(MCModArchive $archive) {
		$version = $archive->Version();

		$archive->copyTo($this->AbsModPath);
		$oldConfigs = $this->scanConfigs();
		
		list($items, $blocks) = $this->generateIDDump();
		list($stdItems, $stdBlocks) = $this->VanillaIDDump;
		
		$archive->deleteFrom($this->AbsModPath);
		
		$hitItems = $newItems = array_diff($items, $stdItems);
		$hitBlocks = $newBlocks = array_diff($blocks, $stdBlocks);
		
		$newConfigs = array_diff($this->scanConfigs(), $oldConfigs);
		
		foreach($newConfigs as $config_file) {
			$config = new MCModConfig();
			$config->ModVersionID = $version->ID;
			$config->Name = basename($config_file);
			$config->Path = substr($this->AbsModPath, '', $config_file);
			$config->Content = file_get_contents($config_file);
			$config->write();
			
			if($config->HasBlocks) {
				foreach(DataObject::get('MCModConfigBlock', 'ConfigID=' . intval($config->ID)) as $block) {
					$id = $item->DefaultValue;
					if(isset($hitBlocks[$id])) {
						unset($hitBlocks[$id]);
						if(isset($hitItems[$id - 256]))
							unset($hitItems[$id - 256]);
					}
				}
			}
			
			if($config->HasItems) {
				foreach(DataObject::get('MCModConfigItem', 'ConfigID=' . intval($config->ID)) as $item) {
					$id = $item->DefaultValue;
					if(isset($hitItems[$id])) {
						unset($hitItems[$id]);
					}
				}
			}
			
			unset($config_file);
		}
		
		$unresolved = array();
		if($hitItems)
			$unresolved['items'] = $hitItems;
		
		if($hitBlocks)
			$unresolved['blocks'] = $hitBlocks;
		
		if(empty($unresolved)) {
			$archive->Resolved = true;
			$archive->write();
		}
	}
	
	public function getVanillaIDDump() {
		return array(
			'items' => json_decode($this->ItemHash),
			'blocks' => json_decode($this->BlockHash),
		);
	}
	
	public function generateIDDump() {
		chdir($this->AbsJarPath);
		$cmd = "/usr/bin/java -Xmx512M -Xms128M -jar {$this->ServerJarPath}";
		$proc = proc_open($cmd, array(array('pipe','r'),array('pipe','w')), $pipes, $this->AbsJarPath);
		
		do {
			sleep(1);
			$status = proc_get_status($proc);
		} while($status['running']);
		
		$out = array();
		
		foreach(file("{$this->AbsJarPath}/itemsList.csv") as $line) {
			$parts = explode(',', $line, 2);
			$out['items'][$parts[0]] = $parts[1];
		}
		
		foreach(file("{$this->AbsJarPath}/blocksList.csv") as $line) {
			$parts = explode(',', $line, 2);
			$out['blocks'][$parts[0]] = $parts[1];
		}
		
		return $out;
	}
	
	public function scanConfigs() {
		return glob($this->AbsConfigPath . '{/*,/*/*,/*/*/*,/*/*/*/*,/*/*/*/*/*,/*/*/*/*/*/*}', GLOB_BRACE);
	}
	
	public function getModuleDir() {
		return realpath(__FILE__ . '../..');
	}
	
	public function downloadServer() {
		$url = "https://s3.amazonaws.com/Minecraft.Download/versions/{$this->Title}/minecraft_server.{$this->Title}.jar";
		if(!folder_exists($folder = $this->ModuleDir . '/minecraft'))
			mkdir($folder);
		
		$name = "minecraft_server.{$this->Title}.jar";
		copy($url, "{$folder}/{$name}");
		
		$this->ServerJarPath = $name;
	}
	
	public function getAbsModPath() {
		return "{$this->ModuleDir}/minecraft/mods";
	}
	
	public function getAbsConfigPath() {
		return "{$this->ModuleDir}/minecraft/config";
	}
	
	public function getAbsJarPath() {
		return "{$this->ModuleDir}/minecraft/{$this->ServerJarPath}";
	}
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		
		if(!$this->ServerJarPath) {
			$this->downloadServer();
			$ids = $this->generateIDDump();
			$this->ItemHash = json_encode($ids['items']);
			$this->BlockHash = json_encode($ids['blocks']);
		}
	}
}