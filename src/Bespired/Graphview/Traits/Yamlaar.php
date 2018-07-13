<?php

namespace Bespired\Graphview\Traits;

trait Yamlaar {

	protected static $lines = [];

	public static function dump($arr) {
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				self::arr($value, $key, 1, 'arr');
			} else {
				self::$lines[] = $key . ': ' . $value;
			}
		}

		array_unshift(self::$lines, '---');
//		array_push(self::$lines, '---');

		return join("\n", self::$lines);
	}

	private static function arr($arr, $skey, $depth, $type) {
		if (!is_numeric($skey)) {
			self::$lines[] = str_repeat(' ', $depth * 4 - 4) . $skey . ':';
		} else {
			$depth--;
		}
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				if (is_numeric($key)) {
					self::$lines[] = str_repeat(' ', $depth * 4 - 4) . '-';
				}
				self::arr($value, $key, $depth + 1, 'arr');
			} else {
				self::$lines[] = str_repeat(' ', $depth * 4) . $key . ': ' . $value;
			}
		}
	}

}
