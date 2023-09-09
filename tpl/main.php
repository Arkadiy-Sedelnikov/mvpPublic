<?php
$input = new Classes\Input();
$ctrl = $input->getString('ctrl');
$action = $input->getString('action', 'index');
?>
<!DOCTYPE HTML>
<html>
<head>
    <title><?php echo $this->title; ?></title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow"/>
    <!-- Bootstrap core CSS -->
    <link href="/tpl/assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/tpl/assets/css/template.css" rel="stylesheet">
    <link href="/tpl/assets/css/jquery.datetimepicker.min.css" rel="stylesheet">

</head>
<body>

<nav class="navbar navbar-expand-xxl navbar-dark bg-dark fixed-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="navbar-brand"><?php echo $this->title; ?></div>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link<?php echo $ctrl == '' && $action == 'index' ? ' active' : ''; ?>"
                   href="/">Загрузка</a>
            </li>
            <li class="nav-item">
                <a class="nav-link<?php echo $ctrl == 'Contractor' ? ' active' : ''; ?>"
                   href="/index.php?ctrl=Contractor">Подрядчик</a>
            </li>
            <li class="nav-item">
                <a class="nav-link<?php echo $ctrl == 'Customer' ? ' active' : ''; ?>"
                   href="/index.php?ctrl=Customer">Заказчик</a>
            </li>
            <li class="nav-item">
                <a class="nav-link<?php echo $ctrl == 'Supplier' ? ' active' : ''; ?>"
                   href="/index.php?ctrl=Supplier">Поставщик</a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="container">
    <?php if($this->error) { ?>
        <div style="position: static; margin-top: 40px;">
            <div class="alert alert-danger" role="alert">
                <?php echo $this->error; ?>
            </div>
        </div>
    <?php } ?>
    <?php if($this->message) { ?>
        <div style="position: static; margin-top: 40px;">
            <div class="alert alert-primary" role="alert">
                <?php echo $this->message; ?>
            </div>
        </div>
    <?php } ?>
    <div class="starter-template">
        <?php echo $this->content; ?>
    </div>

</main><!-- /.container -->
<script src="/tpl/assets/js/jquery-3.5.1.min.js"></script>
<script src="/tpl/assets/js/bootstrap.bundle.min.js"></script>
<script src="/tpl/assets/js/template.js"></script>
<script type="text/javascript" src="/tpl/assets/js/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        lang: 'ru',
        timepicker: false,
        format: 'Y-m-d',
        scrollInput: false,
    });
</script>
</body>
</html>
