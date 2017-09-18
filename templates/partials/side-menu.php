<?php
$pid = $_GET['project_id'] ?? ''; // homophobic pun intended
?>


<aside class="content__side">
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
</aside>
