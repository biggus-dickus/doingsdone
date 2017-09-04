<?php
function parseDataArr($item) {
    return $item !== 'Все';
}

// Ну не вижу я смысла давать юзеру возможность выбирать категорию "Все"...
$projects = array_filter($data[0], 'parseDataArr');

// Validation
function validateEmail($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

function parseData($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = [];
$required = ['task-name', 'task-project', 'task-deadline'];
$rules = ['email' => 'validateEmail'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (in_array($key, $required) && $value === '') {
            $errors[] = $key;
//            $userData[$key] = $value;
//            break;
        }

//        if (in_array($key, $rules)) {
//            $result = call_user_func('validateEmail', $value);
//
//            if (!$result) {
//                $errors[] = $key;
//            }
//        }
    }
}

if (isset($_POST)) {
    $userData = array_slice($_POST, 0);
}

//if (count($errors)) {
//    header('Location: ?add_task=true');
//}

$taskName = $userData['task-name'] ?? '';
$taskDeadline = $userData['task-deadline'] ?? '';

//if (!count($errors)) {
//    header('Location: index.php?success=true');
//}
?>

<pre>
        <?php if(isset($_POST)) {
            print(var_dump($_POST));
            print(var_dump($errors));
            print(var_dump($userData));
        }?>
    </pre>

<div class="modal" <?php if(!isset($_GET['add_task'])):?>hidden<?php endif; ?>>
    <a href="?" class="modal__close" title="Закрыть">Закрыть</a>

    <h2 class="modal__heading">Добавление задачи</h2>

    <mark><?=var_dump($userData)?></mark>

    <form class="form" action="index.php" method="post" enctype="multipart/form-data">
        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input"
                   type="text"
                   name="task-name"
                   id="name"
                   placeholder="Введите название"
                   value="<?=$taskName?>">
        </div>

        <div class="form__row">
            <label class="form__label" for="project">Проект <sup>*</sup></label>

            <select class="form__input form__input--select" name="task-project" id="project">
                <option value="" disabled <?php if(!isset($_POST['task-project'])): ?>selected<?php endif ?>>
                    Выберите проект
                </option>
                <?php foreach($projects as $project):?>
                    <option <?php if(isset($_POST['task-project'])): ?>selected<?php endif ?>>
                        <?=$project?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form__row">
            <label class="form__label" for="date">Дата выполнения <sup>*</sup></label>

            <input class="form__input form__input--date"
                   type="text"
                   name="task-deadline"
                   id="date"
                   placeholder="ДД.ММ.ГГГГ"
                   value="<?=$taskDeadline?>">
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
