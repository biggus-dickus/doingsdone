<!DOCTYPE html>
<html lang="ru">
<?php require_once 'partials/head.php'?>

<body class="
    <?php if(isset($_GET['add_task']) || isset($_GET['sign_in'])):?>
        overlay
    <?php elseif(empty($data['user'])): ?>
        body-background
    <?php endif; ?>">

<h1 class="visually-hidden">Дела в порядке</h1>
<div class="page-wrapper">
    <div class="container <?php if(!empty($data['user'])): ?>container--with-sidebar<?php endif; ?>">

        <!--  Page header -->
        <?php require_once 'partials/header.php'?>

        <div class="content">
            <!--  Side menu -->
            <?php if(!empty($data['user'])):?>
                <?php require_once 'partials/side-menu.php'?>
            <?php endif; ?>

            <!-- Main content -->
            <?php
                if (isset($_GET['project_id']) && !array_key_exists($_GET['project_id'], $data['projects'])) {
                    http_response_code(404);
                    print('Проекта с идентификатором <b>' . $_GET['project_id'] . '</b> не существует.');

                } else if(empty($data['user'])) {
                    require_once 'guest.php';

                } else {
                    print($data['mainContent']);
                }
            ?>
        </div>
    </div>
</div>

<!--  Page footer -->
<?php require_once 'partials/footer.php'?>

<!-- Add new task -->
<?php if(isset($_GET['add_task'])) {
    require_once 'templates/modals/add-task.php';
}
?>

<!-- Sign in -->
<?php if(isset($_GET['sign_in'])) {
    require_once 'templates/modals/sign-in.php';
}
?>

<script src="js/script.js"></script>
</body>
</html>
