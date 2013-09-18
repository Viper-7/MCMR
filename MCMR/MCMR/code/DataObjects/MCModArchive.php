<?php
class MCModArchive extends File {
	public static $belongs_to = array(
		'ModVersion' => 'MCModVersion',
	);
}
