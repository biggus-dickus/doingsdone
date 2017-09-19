<?php
$pid = $_GET['project_id'] ?? '';
?>

<aside class="content__side">
    <?php if(isset($_SESSION['user'])): ?>
    <h2 class="content__side-heading">Проекты</h2>
    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($data['projects'] as $project_id => $project): ?>
                <li class="main-navigation__list-item
                    <?php if((int) $pid === $project_id
                    || !$pid && $project_id === 0): ?>
                        main-navigation__list-item--active
                    <?php endif; ?>">

                    <a <?php if((int) $pid !== $project_id): ?>
                        href="?project_id=<?=$project_id?>"
                        <?php endif; ?>
                        class="main-navigation__list-item-link">
                        <?=$project?>
                    </a>

                    <span class="main-navigation__list-item-count">
                        <?= getTasksAmount($data['tasks'], $project) ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="#">Добавить проект</a>
    <?php endif; ?>

    <?php if(!isset($_SESSION['user']) && isset($_GET['sign_up'])): ?>
        <p class="content__side-info">
            Если у&nbsp;вас уже есть аккаунт, авторизуйтесь на&nbsp;сайте.
        </p>

        <a class="button button--transparent content__side-button" href="?sign_in">Войти</a>
    <?php endif; ?>
</aside>
