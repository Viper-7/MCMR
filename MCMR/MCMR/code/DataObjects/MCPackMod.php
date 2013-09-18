<?php
class MCPackMod extends DataObject {
	public static $has_one = array(
		'PackVersion' => 'MCPackVersion',
		'ModVersion' => 'MCModVersion',
	);
	
	public static $has_many = array(
		'ModConfig' => 'MCModConfig'
	);
}
