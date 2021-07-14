<?php

if (!function_exists('is_list')) {
	function is_list($arr){
		return array_values($arr) === $arr;
	}
}

if (!function_exists('jscandir')) {
	function jscandir($s){
		return array_diff(
			scandir($s),
			['.', '..']
		);
	}
}

if (!function_exists('str_contains')) {
	function str_contains($string, $find){
		if (strpos($string, $find) !== false) {
			return true;
		}
		return false;
	}
}