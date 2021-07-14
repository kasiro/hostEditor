<?php

class Projector {

	public function getHostsProjects($ip, $text, $black = []) {
		$ip = str_replace('.', '\.', $ip);
		$res = preg_match_all("/$ip(\s*|\t)(.*)/m", $text, $match);
		if ($res) {
			$arr = array_filter($match[2], fn($e) => !in_array($e, $black));
			$newArr = array_merge($arr, []);
			// foreach ($arr as $el){
			// 	$newArr[] = $el;
			// }
			return $newArr;
		} else return false;
	}

}

class Editor extends Projector {
	public static $VhostsPath = '';
	public static $hostsPath = '';
	public static $projectPath = '';

	public static $Vhosts = '';
	public static $hostsFile = '';

	public static $ip = '';
	public static $separator = '';

	public function __construct($ip, $separator){
		if (!is_writable(static::$hostsPath)){
			exit('Нет прав');
		}
		if (static::$hostsPath) {
			static::$hostsFile = file_get_contents(static::$hostsPath);
			$this->hostsProjectList = $this->getHostsProjects($ip, static::$hostsFile, [
				'localhost'
			]);
		}
		static::$ip = $ip;
		static::$separator = $separator;
	}

	public function addHosts($name){
		$text = static::$ip.static::$separator.$name;
		if (!str_contains(static::$hostsFile, $text)) {
			file_put_contents(static::$hostsPath, $text.PHP_EOL, FILE_APPEND);
		} else {
			echo "$name уже существует в hosts" . PHP_EOL;
		}
	}

	public function renameHosts($prevName, $name){
		$what = static::$ip.static::$separator.$prevName;
		if (str_contains(static::$hostsFile, $what)) {
			if (!str_contains(static::$hostsFile, $name)){
				$text = static::$ip.static::$separator.$name;
				static::$hostsFile = str_replace($what, $text, static::$hostsFile);
				file_put_contents(static::$hostsPath, static::$hostsFile);
			} else {
				echo "$name уже существует в hosts" . PHP_EOL;
			}
		} else {
			echo "$prevName не существует в hosts" . PHP_EOL;
		}
	}

	public function deleteHosts($name){
		$text = static::$ip.static::$separator.$name;
		if (str_contains(static::$hostsFile, $name)) {
			static::$hostsFile = str_replace($text.PHP_EOL, '', static::$hostsFile);
			file_put_contents(static::$hostsPath, static::$hostsFile);
		}
	}

}
require '/home/kasiro/Документы/projects/mgr/jhp_modules/mfunc.php';
require '/home/kasiro/Документы/projects/mgr/jhp_modules/str.php';
require '/home/kasiro/Документы/projects/mgr/jhp_modules/fs.php';
Editor::$hostsPath = '/etc/hosts';
$editor = new Editor('127.0.0.1', ' ');
$first  = @$argv[1];
$second = @$argv[2];
$third  = @$argv[3];
switch ($first) {
	case 'add':
	case 'block':
		$editor->addHosts($second);
		break;
	
	case 'rename':
		$editor->renameHosts($second, $third);
		break;
	
	case 'delete':
	case 'remove':
	case 'unblock':
		$editor->deleteHosts($second);
		break;

	case 'list':
	case 'block_list':
	case 'blockList':
		if (!empty($editor->hostsProjectList)){
			$pr = $editor->hostsProjectList;
			foreach ($pr as $f){
				echo $f . PHP_EOL;
			}
		} else {
			echo 'not found' . PHP_EOL;
		}
		break;
}