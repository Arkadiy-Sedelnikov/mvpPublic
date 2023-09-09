<?php

namespace Views;

use Exception;

class Main {
    public $content;
    public $message;
    public $error;
    public $title;

    /**
     * @param string $tpl
     * @param array $params
     * @return void
     * @throws Exception
     */
    public function show(string $tpl, array $params = []): void
    {
        if(!is_file(ROOT_PATH . '/' . $tpl)) {
            throw new Exception('Макет ' . $tpl . ' не обнаружен', 500);
        }
        ob_start();
        extract($params);
        require ROOT_PATH . '/' . $tpl;
        $this->content = ob_get_clean();
        require ROOT_PATH . '/tpl/main.php';
    }
}