<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get">
        <input type="hidden" name="form_name" value="search_form">
        <input class="search-form__input" name="search_query" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" value="Искать">
    </form>

    <div class="tasks-controls">
        <div class="radio-button-group">
            <label class="radio-button">
                <input class="radio-button__input visually-hidden" type="radio" name="radio" checked="">
                <span class="radio-button__text">Все задачи</span>
            </label>

            <label class="radio-button">
                <input class="radio-button__input visually-hidden" type="radio" name="radio">
                <span class="radio-button__text">Повестка дня</span>
            </label>

            <label class="radio-button">
                <input class="radio-button__input visually-hidden" type="radio" name="radio">
                <span class="radio-button__text">Завтра</span>
            </label>

            <label class="radio-button">
                <input class="radio-button__input visually-hidden" type="radio" name="radio">
                <span class="radio-button__text">Просроченные</span>
            </label>
        </div>

        <label class="checkbox">
            <input id="show-complete-tasks"
                   class="checkbox__input visually-hidden"
                   type="checkbox"
                   <?php if($data['showCompleted']):?>checked<?php endif; ?>
            >
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <table class="tasks">
        <?php foreach ($data['tasks'] as $task): ?>
            <?php if (
                    isset($_GET['project_id']) && $data['projects'][$_GET['project_id']] === $task['project']
                    || !isset($_GET['project_id'])
                    || (int) $_GET['project_id'] === 0): ?>
                <tr class="tasks__item
                <?php if($task['isDone'] && $data['showCompleted']): ?>
                    task--completed
                <?php elseif($task['isDone']): ?>
                    hidden
                <?php endif; ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input class="checkbox__input visually-hidden" type="checkbox">
                            <span class="checkbox__text"><?= htmlspecialchars($task['taskName']) ?></span>
                        </label>
                    <td class="task__date">
                        <time datetime="<?= date('Y-m-d', strtotime($task['deadline'])); ?>">
                            <?=$task['deadline']?>
                        </time>
                    </td>

                    <td class="task__controls">
                        <button class="expand-control" type="button" name="button">
                            <?= htmlspecialchars($task['taskName']) ?>
                        </button>
                        <ul class="expand-list hidden">
                            <li class="expand-list__item">
                                <a href="#">Выполнить</a>
                            </li>
                            <li class="expand-list__item">
                                <a href="#">Удалить</a>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</main>
