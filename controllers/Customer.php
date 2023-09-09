<?php
namespace Controllers;

use Classes\MainController;
use Exception;
use Models\Messages;
use Models\Orders;
use Models\Requests;

class Customer extends MainController
{
    /**
     * @throws Exception
     */
    public function index(){
        $Messages = new Messages();
        $Requests = new Requests();
        $Orders = new Orders();
        $messages = $Messages->getRows([], '*', 'id', 0, 0, 'id desc');
        $requests = $Requests->getRows([], '*', 'message_id');
        $orders = $Orders->getRows([], '*', 'message_id');

        $data = [];
        foreach ($messages as $messageId => $message) {
            $data[] = [
                'message' => $message,
                'request' => $requests[$messageId] ?? [],
                'order' => $orders[$messageId] ?? [],
            ];
        }
        $view = new \Views\Main();
        $view->message = $this->getMessage();
        $view->error = $this->getError();
        $view->show('tpl/customer.php', compact('data'));
    }

    public function sent()
    {
        $input = new \Classes\Input();
        $id = $input->getInt('id');
        $redirect = '/index.php?ctrl=Customer';
        if (!$id) {
            $this->addError('ID сообщения пуст');
            $this->redirect($redirect);
        }
        $Orders = new Orders();
        $Requests = new Requests();
        $request = $Requests->getRow(['id' => $id]);
        if (empty($request['id'])) {
            $this->addError('Не найдена заявка');
            $this->redirect($redirect);
        }
        if ($request['sent']) {
            $this->addError('Заявка уже отправлена');
            $this->redirect($redirect);
        }

        $data = [
            'message_id' => $request['message_id'],
            'request_id' => $id,
            'delivery_date' => '',
            'accepted' => 0,
        ];
        if (!$Orders->add($data)) {
            $this->addError('Ошибка создания заявки');
        } else {
            $Requests->update(['sent' => 1], ['id' => $id]);
            $this->addMessage('Заявка создана');
        }
        $this->redirect($redirect);
    }
}