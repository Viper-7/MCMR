<?php
class MCVersion extends DataObject {
	public static $db = array(
		'Title' => 'Varchar',
	);
	
	public static $belongs_many_many = array(
		'Mods' => 'MCMod',
	);
}