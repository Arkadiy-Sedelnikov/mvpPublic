<?php foreach ($data as $v) { ?>
        <?php $message = $v['message']; ?>
        <?php $order = $v['order']; ?>
        <div class="jumbotron">
            <div class="alert alert-danger" role="alert">
                <?php echo 'Сообщение #: ' . $message['id'] . '; '; ?>
                <?php echo 'Скважина: ' . $message['well'] . '; '; ?>
                <?php echo 'Оборудование: ' . $message['equipment'] . '; '; ?>
                <?php echo 'Часть: ' . $message['product'] . '; '; ?>
                <?php echo 'Неисправность: ' . $message['failure'] . '; '; ?>
            </div>
            <form class="form-inline"  method="post" action="/index.php?ctrl=Supplier&action=sent">
                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                <div class="form-group mb-2">
                    <label for="staticEmail2" class="sr-only">Дата поставки</label>
                    <input type="text"
                           class="form-control-plaintext datetimepicker"
                           id="staticEmail2"
                           name="delivery_date"
                           value="<?php echo $order['delivery_date'] == '0000-00-00' ? '' : $order['delivery_date']; ?>">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Принять</button>
            </form>
        </div>
<?php } ?>


