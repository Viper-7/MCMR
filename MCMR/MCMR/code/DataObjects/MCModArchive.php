<?php
class MCModArchive extends File {
	public static $has_one = array(
		'ModVersion' => 'MCModVersion',
	);
}
