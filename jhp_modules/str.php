<?php

// function str_contains($string, $find){
//     if (strpos($string, $find) !== false) {
//        	return true;
//     }
// 	return false;
// }

class str {
	
	public static function contains($string, $find){
		if (strpos($string, $find) !== false) {
			return true;
		}
		return false;
	}

	public static function random($count, $string){
		$text = '';
		for ($i = 1; $i <= $count; $i++) { 
			$text .= $string[rand(0, strlen($string) - 1)];
		}
		return $text;
	}

	private static function dist($a, $b)
	{
		$i = strlen($a);
		$j = strlen($b);
		function rec($i, $j)
		{
			global $a, $b;
			if ($i == 0 || $j == 0) {
				return max($i, $j);
			} elseif ($a[$i-1] == $b[$j-1]) {
				return rec($i-1, $j-1);
			} else {
				return 1 + min(
					rec($i, $j-1),
					rec($i-1, $j),
					rec($i-1, $j-1)
				);
			}
		}
		return rec($i, $j);
	}

	public static function show_sh($s1, $s2){
		$lev = str::dist($s1, $s2);
		$big = max([strlen($s1), strlen($s2)]);
		$ptc = (($big - $lev) / $big) * 100;
		return floor($ptc) . '%';
	}

	/**
	 * Пример: $str = str::sprintf2('my name %name', ['name' => 'jhon'])
	 * Вывод/Результат: $str == 'my name jhon'
	 */
	public static function sprintf2($str, $vars, $char = '%'){
		if(is_array($vars)){
			foreach($vars as $k => $v){
				$str = str_replace($char . $k, $v, $str);
			}
		}
		return $str;
	}
	
	public static function strtr($text, $from, $to){
		$newText = '';
		$zam = [];
		$text_arr = [];
		if (strlen($from) !== strlen($to)) return false;
		for ($i = 0; $i < strlen($from); $i++) {
			$zam[$from[$i]] = $to[$i];
		}
		for ($i = 0; $i < strlen($text); $i++) { 
			$text_arr[] = $text[$i];
		}
		foreach ($text_arr as $char) { 
			if (isset($zam[$char])){
				$newText .= $zam[$char];
			} else {
				$newText .= $char;
			}
		}
		return $newText;
	}
}