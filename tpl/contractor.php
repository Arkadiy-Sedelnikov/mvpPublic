<div class="row">
    <div class="col-sm">
        <img src="/tpl/assets/images/image2.png" style="max-width: 100%;">
    </div>
    <div class="col-sm">
        <?php foreach ($data as $v) { ?>
            <?php $message = $v['message']; ?>
            <?php $request = $v['request']; ?>
            <?php $order = $v['order']; ?>
            <div class="jumbotron">
                <div class="alert alert-danger" role="alert">
                    <?php echo '#: ' . $message['id'] . '; '; ?>
                    <?php echo 'Скважина: ' . $message['well'] . '; '; ?>
                    <?php echo 'Оборудование: ' . $message['equipment'] . '; '; ?>
                    <?php echo 'Часть: ' . $message['product'] . '; '; ?>
                    <?php echo 'Неисправность: ' . $message['failure'] . '; '; ?>
                </div>
                <?php if (!$message['sent']) { ?>
                    <a class="btn btn-primary" href="/index.php?ctrl=Contractor&action=sent&id=<?php echo $message['id']; ?>">Сформировать запрос</a>
                <?php } ?>

                <?php if(!empty($request)) { ?>
                    <div class="alert alert-primary" role="alert">
                        <?php echo 'Запрос #: ' . $request['id'] . ' сформирован;'; ?>
                    </div>
                <?php } ?>
                <?php if (!empty($order)) { ?>
                    <?php if (!$order['accepted']) { ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo 'Заказ #: ' . $order['id'] . '; '; ?>
                        <?php echo 'Принят: ' . ($order['accepted'] ? 'Да' : 'Нет') . '; '; ?>
                        <?php echo 'Дата поставки: ' . ($order['delivery_date'] == '0000-00-00' ? '-' : $order['delivery_date']) . '; '; ?>
                    </div>
                    <?php } else {
                        $orders = [];
                        for ($i = 1; $i < 6; $i++) {
                            $orders[] = [
                                    'Поставщик' => $i,
                                    'Рейтинг' => rand(100, 600),
                                    'Цена' => rand(1, 6),
                                    'Дата поставки' => ($order['delivery_date'] == '0000-00-00' ? '-' : $order['delivery_date']),
                            ];
                        }
                        $best = 0;
                        foreach ($orders as $key => $order) {
                            if($key == 0) {
                                continue;
                            }
                            if ($order['Цена'] == $orders[$best]['Цена']) {
                                if ($order['Рейтинг'] > $orders[$best]['Рейтинг']) {
                                    $best = $key;
                                }
                            } elseif ($order['Цена'] < $orders[$best]['Цена']) {
                                $best = $key;
                            }
                        }
                        foreach ($orders as $key => $order) {
                            $class = $best == $key ? 'success' : 'warning';
                            ?>
                            <div class="alert alert-<?php echo $class; ?>" role="alert">
                                <?php echo 'Поставщик: ' . $order['Поставщик'] . '; '; ?>
                                <?php echo 'Рейтинг: ' . $order['Рейтинг'] . '; '; ?>
                                <?php echo 'Дата поставки: ' . $order['Дата поставки'] . '; '; ?>
                                <?php echo 'Цена: ' . $order['Цена'] . ' млн; '; ?>
                            </div>
                        <?php } ?>

                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
    <div class="col-sm">
        <img src="/tpl/assets/images/image3.png" style="width: 100%">
        <img src="/tpl/assets/images/image5.png" style="width: 100%">
    </div>
</div>

