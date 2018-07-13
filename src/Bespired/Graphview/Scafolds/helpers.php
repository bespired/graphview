<?php

// deep object convert
if (!function_exists('objectify')) {
	function objectify($array) {
		return json_decode(json_encode($array), false);
	}
}

// even numbered arrays will become objects...
if (!function_exists('dictionairify')) {
	function dictionairify($array) {
		return json_decode(json_encode($array, JSON_FORCE_OBJECT), false);
	}
}

if (!function_exists('arrays')) {
	function arrays($array) {
		return json_decode(json_encode($array), true);
	}
}

if (!function_exists('is_config')) {
	function is_config($config, $variable) {
		return null !== config(sprintf($config, $variable));
	}
}

if (!function_exists('str_name')) {
	function str_name($name) {
		return str_replace('-', '_', str_slug($name));
	}
}

if (!function_exists('not_nulls')) {
	function not_nulls($array) {
		return array_filter($array,
			function ($var) {
				return !is_null($var);
			}
		);
	}
}
