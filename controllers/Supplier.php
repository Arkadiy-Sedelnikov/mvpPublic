<?php
namespace Controllers;

use Classes\MainController;
use Exception;
use Models\Messages;
use Models\Orders;
use Models\Requests;

class Supplier extends MainController
{
    /**
     * @throws Exception
     */
    public function index(){
        $Messages = new Messages();
        $Requests = new Requests();
        $Orders = new Orders();
        $messages = $Messages->getRows([], '*', 'id');
        $orders = $Orders->getRows([], '*', '', 0, 0, 'id desc');

        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                'order' => $order,
                'message' => $messages[$order['message_id']] ?? [],
            ];
        }
        $view = new \Views\Main();
        $view->message = $this->getMessage();
        $view->error = $this->getError();
        $view->show('tpl/supplier.php', compact('data'));
    }

    public function sent()
    {
        $input = new \Classes\Input();
        $id = $input->getInt('id');
        $delivery_date = $input->getString('delivery_date');
        $redirect = '/index.php?ctrl=Supplier';
        if (!$id) {
            $this->addError('ID заказа пуст');
            $this->redirect($redirect);
        }
        if (!$delivery_date) {
            $this->addError('Дата пуста');
            $this->redirect($redirect);
        }
        $Orders = new Orders();
        $order = $Orders->getRow(['id' => $id]);
        if (empty($order['id'])) {
            $this->addError('Не найден заказ');
            $this->redirect($redirect);
        }

        $data = [
            'delivery_date' => $delivery_date,
            'accepted' => 1,
        ];

        if (!$Orders->update($data, ['id' => $id])) {
            $this->addError('Ошибка обновления заказа');
        } else {
            $this->addMessage('Заказ обновлен');
        }
        $this->redirect($redirect);
    }
}