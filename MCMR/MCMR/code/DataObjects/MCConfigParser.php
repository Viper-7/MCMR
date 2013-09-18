<?php
class MCConfigParser {
	public static function parse($config_content) {
		$output = array();
		$sectionStack = array();
		$match = array();
		$inList = false;
		$lines = array_map('trim', explode("\n", $config_content));
		
		foreach($lines as $line) {
			switch(true) {
				case $inList:
					if(preg_match('/^\s*>\s*$/', $line)) {
						$output[implode('.', $sectionStack)][$match[2]] = array($match[1], $list);
						$inList = false;
						$list = array();
					} else {
						$list[] = trim($line);
					}
					break;
				case preg_match('/^[\s"]*([^"{]+)[\s"]*\s*\{\s*$/', $line, $match):
					$sectionStack[] = trim($match[1]);
					break;
				case preg_match('/^\s*\}\s*$/', $line, $match):
					array_pop($sectionStack);
					break;
				case preg_match('/^\s*([DBSI]):((?:"[^"]+?")|(?:[^=<]+?))\s*=\s*(.*?)\s*$/', $line, $match):
					$output[implode('.', $sectionStack)][$match[2]] = array($match[1], $match[3]);
					break;
				case preg_match('/^\s*([DBSI]):((?:"[^"]+?")|(?:[^=<]+?))\s*<\s*$/', $line, $match):
					$inList = true;
					$list = array();
					break;
			}
		}
		
		return $output;
	}
	
	public static function update($config_content, $settings) {
		$output = array();
		$sectionContent = array();
		$lines = array_map('trim', explode("\n", $config_content));
		$inList = false;
		
		foreach($lines as $line) {
			switch(true) {
				case $inList:
					if(preg_match('/^\s*>\s*$/', $line)) {
						$inList = false;
						$output[] = $line;
					}
					break;
				case preg_match('/^[\s"]*([^"{]+)[\s"]*\s*\{\s*$/', $line, $match):
					$sectionStack[] = trim($match[1]);
					if(isset($settings[implode('.', $sectionStack)])) {
						$sectionContent = $settings[implode('.', $sectionStack)];
					}
					$output[] = $line;
					break;
				case preg_match('/^\s*\}\s*$/', $line, $match):
					array_pop($sectionStack);
					
					if(isset($settings[implode('.', $sectionStack)])) {
						$sectionContent = $settings[implode('.', $sectionStack)];
					}
					
					$output[] = $line;
					break;
				case preg_match('/^(\s*)([DBSI]):((?:"[^"]+?")|(?:[^=<]+?))\s*=\s*.*?\s*$/', $line, $match):
					if(isset($sectionContent[$match[2]][1])) {
						$output[] = "{$match[0]}{$match[1]}:{$match[2]}={$sectionContent[$match[2]][1]}";
					} else {
						$output[] = $line;
					}
					break;
				case preg_match('/^(\s*)([DBSI]):((?:"[^"]+?")|(?:[^=<]+?))\s*<\s*$/', $line, $match):
					if(isset($sectionContent[$match[2]])) {
						$inList = true;
						$output[] = "{$match[0]}{$match[1]}:{$match[2]} <";
						foreach($sectionContent[$match[2]][1] as $item) {
							$output[] = $match[0] . '    ' . $item;
						}
					} else {
						$output[] = $line;
					}
					break;
				default:
					$output[] = $line;
					break;
			}
		}
		
		return implode("\r\n", $output);
	}
}
