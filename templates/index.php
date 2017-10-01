<?php
function formatDate($date) {
    $f_date = (is_null($date) || strtotime($date) === 0) ? '—' : date('d.m.Y', strtotime($date));

    return $f_date;
}

$query = (isset($_GET['q'])) ? parseUserInput($_GET['q']) : null;
$punctuation = (count($data['tasks']) === 0) ? '.' : ':';

$queryLength = count($data['tasks']);

switch ($queryLength) {
    case 1:
        $res = 'результат';
        break;
    case 2:
    case 3:
    case 4:
        $res = 'результата';
        break;
    default:
        $res = 'результатов';
}
?>


<main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="get">
        <input class="search-form__input" name="q" placeholder="Поиск по задачам">
        <input class="search-form__submit" type="submit" value="Искать">
    </form>

    <div class="tasks-controls">
        <div class="radio-button-group">
            <label class="radio-button">
                <input class="radio-button__input visually-hidden"
                       type="radio"
                       name="radio"
                       value="all"
                       <?php if(!isset($_GET['show_tasks'])
                           ||isset($_GET['show_tasks']) && $_GET['show_tasks'] === 'all'):?>checked<?php endif; ?>
                >
                <span class="radio-button__text">Все задачи</span>
            </label>

            <label class="radio-button">
                <input class="radio-button__input visually-hidden"
                       type="radio"
                       name="radio"
                       value="today"
                       <?php if(isset($_GET['show_tasks']) && $_GET['show_tasks'] === 'today'):?>checked<?php endif; ?>
                >
                <span class="radio-button__text">Повестка дня</span>
            </label>

            <label class="radio-button">
                <input class="radio-button__input visually-hidden"
                       type="radio"
                       name="radio"
                       value="tomorrow"
                       <?php if(isset($_GET['show_tasks']) && $_GET['show_tasks'] === 'tomorrow'):?>checked<?php endif; ?>
                >
                <span class="radio-button__text">Завтра</span>
            </label>

            <label class="radio-button">
                <input class="radio-button__input visually-hidden"
                       type="radio"
                       name="radio"
                       value="overdue"
                       <?php if(isset($_GET['show_tasks']) && $_GET['show_tasks'] === 'overdue'):?>checked<?php endif; ?>
                >
                <span class="radio-button__text">Просроченные</span>
            </label>
        </div>

        <label class="checkbox">
            <input id="show-completed-tasks"
                   class="checkbox__input visually-hidden"
                   type="checkbox"
                   <?php if($data['showCompleted']):?>checked<?php endif; ?>
            >
            <span class="checkbox__text">Показывать выполненные</span>
        </label>
    </div>

    <?php if(isset($_GET['q'])): ?>
        <p>
            По запросу <i>&laquo;<?=$query?>&raquo;</i> найдено <?=count($data['tasks']) .'&nbsp;'.$res.$punctuation?>
        </p>
    <?php endif;?>

    <table class="tasks">
        <?php foreach ($data['tasks'] as $task): ?>
            <?php if (
                    isset($_GET['project_id']) && (int) $_GET['project_id'] === $task['project_id']
                    || !isset($_GET['project_id'])
                    || (int) $_GET['project_id'] === 0): ?>
                <tr class="tasks__item
                <?php if($task['completed_on']): ?>
                    task--completed
                <?php endif; ?>">
                    <td class="task__select">
                        <label class="checkbox task__checkbox">
                            <input
                                    class="checkbox__input visually-hidden js-completion-toggler"
                                    data-task-id="<?=$task['id']?>"
                                    type="checkbox"
                                    <?php if($task['completed_on']):?>checked<?php endif; ?>>
                            <span class="checkbox__text"><?= htmlspecialchars($task['name']) ?></span>
                        </label>

                        <?php if($task['attachment_URL']):?>
                            <a href="<?=$task['attachment_URL']?>"
                               class="task__file"
                               target="_blank"
                               title="Загрузить вложение">
                                Загрузить вложение
                            </a>
                        <?php endif; ?>
                    </td>

                    <td class="task__date">
                        <time datetime="<?=$task['deadline']?>">
                            <?= formatDate($task['deadline']); ?>
                        </time>
                    </td>

                    <td class="task__controls">
                        <button class="expand-control" type="button" name="button">Дополнительные действия</button>
                        <ul class="expand-list hidden">
                            <li class="expand-list__item">
                                <?php if($task['completed_on']): ?>
                                    <a href="?complete_task=0&task_id=<?=$task['id']?>">
                                        Отметить как невыполненную
                                    </a>
                                <?php else: ?>
                                    <a href="?complete_task=1&task_id=<?=$task['id']?>">
                                        Выполнить
                                    </a>
                                <?php endif; ?>
                            </li>
                            <li class="expand-list__item">
                                <a href="?delete_task=<?=$task['id']?>">
                                    Удалить
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </table>
</main>
