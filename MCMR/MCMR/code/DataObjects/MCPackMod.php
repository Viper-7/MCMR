<?php
class MCPackMod extends DataObject {
	public static $has_one = array(
		'PackVersion' => 'MCPackVersion',
		'ModVersion' => 'MCModVersion',
		'ModConfig' => 'MCModConfig'
	);
	
	public static $has_many = array(
		'ModConfigValue' => 'MCModConfigSettingValue',
	);
}
