<?php
namespace Classes;

class Input
{
	function getString($name, $default=null){
		$data = isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
		return (string)filter_var($data, FILTER_SANITIZE_STRING);
	}

	function getInt($name, $default=null){
		$data = isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
		return (int)filter_var($data, FILTER_SANITIZE_NUMBER_INT);
	}

	function getFloat($name, $default=null){
		$data = isset($_REQUEST[$name]) ? $_REQUEST[$name] : $default;
		return (float)filter_var($data, FILTER_SANITIZE_NUMBER_FLOAT);
	}

    /**
     * @param $type - Одна из констант INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER или INPUT_ENV.
     * @param $definition
     * @return float
     */
	function getArray($type, $definition){
        return filter_input_array($type, $definition);
	}
}