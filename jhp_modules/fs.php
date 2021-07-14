<?php

class fs {
	public static function folder_copy($from, $to, $black_list = []) { 
		$dir = opendir($from);
		@mkdir($to);
		while(false !== ($file = readdir($dir)) ) { 
			if (($file != '.' ) && ($file != '..' )) { 
				if (is_dir($from . '/' . $file)) {
					static::folder_copy($from . '/' . $file, $to . '/' . $file, $black_list);
				} else {
					if (!in_array($file, $black_list)) {
						copy($from . '/' . $file, $to . '/' . $file);
					}
				}
			} 
		} 
		closedir($dir);
	}
	
	public static function leveling($path, $level = 0){
		if ($path == './' || $path == '/') {
			return __DIR__;
		} else {
			if ($level > 0) {
				$npath = '';
				if (str_contains($path, '/')) {
					$sep = '/';
				}
				if (str_contains($path, '\\')) {
					$sep = '\\';
				}
				if ($level < count(explode($sep, $path))) {
					$arr = explode($sep, $path);
					for ($i = 0; $i < count($arr) - ($level); $i++) { 
						if ($i != count($arr) - ($level) - 1) {
							$npath .= $arr[$i] . $sep;
						} else {
							$npath .= $arr[$i];
						}
					}
					if ((count(explode($sep, $path)) - 1) == $level) {
						$npath .= $sep;
					}
					if (strlen($npath) > 0) {
						return $npath;
					}
				} else {
					echo 'ERROR: $level > count(explode($sep, $path))' . "\n";
				}
			} else {
				return $path;
			}
		}
	}

	public static function folder_tree($path){
		$files = scandir($path);
		$all = [];
		foreach ($files as $file){
			if (!in_array($file, ['.', '..'])) {
				$rpath = $path . '/' . $file;
				if (is_dir($rpath)) {
					$all[$file] = static::folder_tree($rpath);
				} else {
					$all[] = $file;
				}
			}
		}
		return $all;
	}

	public static function rglob($base, $pattern, $flags = 0) {
		if (substr($base, -1) !== DIRECTORY_SEPARATOR) {
			$base .= DIRECTORY_SEPARATOR;
		}
	
		$files = glob($base.$pattern, $flags);
		
		foreach (glob($base.'*', GLOB_ONLYDIR|GLOB_NOSORT|GLOB_MARK) as $dir) {
			$dirFiles = static::rglob($dir, $pattern, $flags);
			if ($dirFiles !== false) {
				$files = array_merge($files, $dirFiles);
			}
		}
	
		return $files;
	}

	public static function clean($path, $echo = false, $black_list = []){
		$dir = $path;
		$di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
		$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ($ri as $path => $file) {
			if ($echo === false) {
				if ($file->isDir()) {
					if (!in_array(basename($file), $black_list)){
						rmdir($file);
					}
				} else {
					if (!in_array(basename($file), $black_list)) {
						unlink($file);
					}
				}
			} else {
				if ($file->isDir()) {
					if (!in_array(basename($file), $black_list)){
						rmdir($file);
						echo 'folder: ' . basename($file) . ' deleted...' . "\n";
					}
				} else {
					if (!in_array(basename($file), $black_list)) {
						unlink($file);
						echo 'file: ' . basename($file) . ' deleted...' . "\n";
					}
				}
			}
		}
		return true;
	}

	public static function delete($path){
		if (is_dir($path)) {
			rmdir($path);
		} else {
			unlink($path);
		}
		return true;
	}

	public static function nameNoExt(string $path){
		if (is_file($path) && preg_match('/.+\..+/', $path)) {
			return explode('.', basename($path))[0];
		} else {
			return false;
		}
	}

	public static function ext(string $path){
		if (preg_match('/.+\..+/', $path)) {
			return substr(strrchr($path, '.'), 1);
		} else {
			return false;
		}
	}
}