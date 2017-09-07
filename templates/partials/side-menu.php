<aside class="content__side">
    <h2 class="content__side-heading">Проекты</h2>
    <nav class="main-navigation">
        <ul class="main-navigation__list">
            <?php foreach ($data['projects'] as $project_id => $project): ?>
                <li class="main-navigation__list-item
                    <?php if( isset($_GET['project_id']) && (int) $_GET['project_id'] === $project_id
                    || !isset($_GET['project_id']) && $project_id === 0) : ?>
                        main-navigation__list-item--active
                    <?php endif; ?>">

                    <a href="?project_id=<?=$project_id?>" class="main-navigation__list-item-link"><?=$project?></a>

                    <span class="main-navigation__list-item-count">
                        <?= getTasksAmount($data['tasks'], $project) ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="#">Добавить проект</a>
</aside>
