<?php
namespace Controllers;

use Classes\Input;
use Classes\MainController;
use Exception;
use Models\Messages;
use Models\Orders;
use Models\Requests;

class Contractor extends MainController
{
    /**
     * @throws Exception
     */
    public function index(){
        $model = new Messages();
        $Requests = new Requests();
        $Orders = new Orders();
        $messages = $model->getRows([], '*', '', 0, 0, 'id desc');
        $requests = $Requests->getRows([], '*', 'message_id');
        $orders = $Orders->getRows([], '*', 'message_id');
        $data = [];
        foreach ($messages as $message) {
            $messageId = $message['id'];
            $data[] = [
                'message' => $message,
                'request' => $requests[$messageId] ?? [],
                'order' => $orders[$messageId] ?? [],
            ];
        }

        $view = new \Views\Main();
        $view->message = $this->getMessage();
        $view->error = $this->getError();
        $view->show('tpl/contractor.php', compact('data'));
    }

    public function sent()
    {
        $input = new Input();
        $id = $input->getInt('id');
        $redirect = '/index.php?ctrl=Contractor';
        if (!$id) {
            $this->addError('ID сообщения пуст');
            $this->redirect($redirect);
        }
        $Messages = new Messages();
        $Requests = new Requests();
        $message = $Messages->getRow(['id' => $id]);
        if (empty($message['id'])) {
            $this->addError('Не найдено сообщение');
            $this->redirect($redirect);
        }
        if ($message['sent']) {
            $this->addError('Запрос уже отправлен');
            $this->redirect($redirect);
        }

        $data = [
            'message_id' => $id,
            'well' => $message['well'],
            'equipment' => $message['equipment'],
            'product' => $message['product'],
            'failure' => $message['failure'],
            'sent' => 0,
        ];
        if (!$Requests->add($data)) {
            $this->addError('Ошибка создания запроса');
        } else {
            $Messages->update(['sent' => 1], ['id' => $id]);
        }
        $this->redirect($redirect);
    }
}