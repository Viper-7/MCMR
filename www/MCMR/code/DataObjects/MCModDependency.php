<?php
class MCModDependency extends DataObject {
	public static $has_one = array(
		'DependsOn' => 'MCModVersion',
	);
	
	public static $belongs_to = array(
		'ModVersion' => 'MCModVersion',
	);
}
