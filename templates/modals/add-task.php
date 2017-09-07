<?php
function parseDataArr($item) {
    return $item !== 'Все';
}

$projects = array_filter($data['projects'], 'parseDataArr');

// Required fields
$taskName = $_POST['taskName'] ?? '';
$taskDeadline = $_POST['deadline'] ?? '';
$taskProject = $_POST['project'] ?? '';

// Error messages
$errorTaskName = $data['errors']['taskName'] ?? '';
$errorTaskDeadline = $data['errors']['deadline'] ?? '';
?>

<div class="modal">
    <a href="?" class="modal__close" title="Закрыть">Закрыть</a>

    <h2 class="modal__heading">Добавление задачи</h2>

    <form class="form" action="../index.php" method="post" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?php if($errorTaskName):?>form__input--error<?php endif; ?>"
                   type="text"
                   name="taskName"
                   id="name"
                   placeholder="Введите название"
                   value="<?=$taskName?>">

            <p class="error-message"><?=$errorTaskName?></p>
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select" name="project" id="project">
                <?php foreach($projects as $project):?>
                    <option <?php if($taskProject === $project): ?>selected<?php endif; ?>>
                        <?=$project?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>

            <input class="form__input form__input--date <?php if($errorTaskDeadline):?>form__input--error<?php endif; ?>"
                   type="text"
                   name="deadline"
                   id="date"
                   placeholder="ДД.ММ.ГГГГ"
                   value="<?=$taskDeadline?>">

            <p class="error-message"><?=$errorTaskDeadline?></p>
        </div>

        <div class="form__row">
            <label class="form__label" for="file">Файл</label>

            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="task-attachment" id="preview" value="">

                <label class="button button--transparent" for="preview">
                    <span>Выберите файл</span>
                </label>
            </div>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" name="" value="Добавить">
        </div>
    </form>
</div>
