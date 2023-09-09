<?php

namespace Classes;

class Session
{
    private static $instance = null;

    private static $error_key = 'errors';
    private static $message_key = 'errors';

    private function __construct() {
        session_start();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function get($key, $delete = false) {
        $value = $this->get_param($_SESSION['storage'], $key);
        if($delete){
            unset($_SESSION['storage'][$key]);
        }
        return $value;
    }

    public function set($key, $val) {
        $_SESSION['storage'][$key] = $val;
    }

    public function delete ($key) {
        unset ($_SESSION['storage'][$key]);
    }

    public function destroy() {
        unset($_SESSION['storage']);
    }

    public function setError($val) {
        $_SESSION['storage'][self::$error_key] = $val;
    }

    public function getError($delete = true) {
        $error = !empty($_SESSION['storage'][self::$error_key]) ? $_SESSION['storage'][self::$error_key] : '';
        if($delete)	unset($_SESSION['storage'][self::$error_key]);
        return $error;
    }

    public function setMessage($val) {
        $_SESSION['storage'][self::$message_key] = $val;
    }

    public function getMessage($delete = true) {
        $error = !empty($_SESSION['storage'][self::$message_key]) ? $_SESSION['storage'][self::$message_key] : '';
        if($delete)	unset($_SESSION['storage'][self::$message_key]);
        return $error;
    }

    private function get_param(&$arr, $key, $default = null)
    {
        if (is_array($arr) && array_key_exists($key, $arr)) {
            return $arr[$key];
        } else {
            return $default;
        }
    }
}