<?php
namespace Classes;

class MainController
{
    public function index(){
        $view = new \Views\Main();
        $view->message = $this->getMessage();
        $view->error = $this->getError();
        $view->show('tpl/index.php');
    }

    public function redirect($url)
    {
        header('Location: ' . $url);
        exit;
    }

    public function addMessage($message) {
        $session = \Classes\Session::getInstance();
        $session->setMessage($message);
    }

    public function addError($message) {
        $session = \Classes\Session::getInstance();
        $session->setError($message);
    }

    public function getMessage() {
        $session = \Classes\Session::getInstance();
        return $session->getMessage();
    }

    public function getError() {
        $session = \Classes\Session::getInstance();
        return $session->getError();
    }
}