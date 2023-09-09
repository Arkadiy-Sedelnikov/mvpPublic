<?php
namespace Controllers;

use Classes\MainController;
use Exception;
use Models\Messages;

class Index extends MainController
{

    /**
     * @throws Exception
     */
    public function load()
    {
        $model = new Messages();
        $file = ROOT_PATH . '/xml/message.xml';
        $content = new \SimpleXMLElement(file_get_contents($file));
        $data = [];
        foreach ($content as $log) {
            $data[] = [
                'well' => (string)$log->well,
                'equipment' => (string)$log->equipment,
                'product' => (string)$log->product,
                'failure' => (string)$log->failure,
            ];
        }
        foreach ($data as $v) {
            $model->add($v);
        }

        $this->addMessage('Загрузка файла.<br> Загрузка завершена.<br> Обработка файла.<br> Обработка завершена.<br> Сообщения добавлены.');
        $this->redirect('/');
    }
}