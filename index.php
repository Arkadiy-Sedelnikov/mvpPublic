<?php

define('ROOT_PATH', dirname(__FILE__));
require_once ROOT_PATH.'/vendor/autoload.php';

try {
    $input = new Classes\Input();
    $config = new \Config();
    $ctrl = $input->getString('ctrl');
    $action = $input->getString('action', 'index');
    $controller = '\Controllers\Index';
    if(!empty($ctrl)){
        if(is_file(ROOT_PATH.'/controllers/'.$ctrl.'.php')){
            $controller = '\Controllers\\' . $ctrl;
        } else {
            throw new \Exception('Вызываемого конртоллера не существует', 400);
        }
    }
    $controller = new $controller();
    if(!method_exists($controller, $action)) {
        throw new \Exception('Вызываемого метода не существует', 400);
    }
    $controller->$action();
} catch (Exception $e){
    echo '<pre>';echo $e->getCode().' '.$e->getMessage();echo '</pre>';
}
