<?php
$errorProjectName = $data['errors']['projectName'] ?? '';
?>

<div class="modal">
    <a href="?" class="modal__close" title="Закрыть">Закрыть</a>

    <h2 class="modal__heading">Добавление проекта</h2>

    <form class="form" action="index.php" method="post">
        <input type="hidden" name="form_name" value="add_project">

        <div class="form__row">
            <label class="form__label" for="name">Название <sup>*</sup></label>

            <input class="form__input <?php if($errorProjectName):?>form__input--error<?php endif; ?>"
                   type="text"
                   name="projectName"
                   id="name"
                   placeholder="Введите название"
                   minlength="3"
                   maxlength="128"
                   autofocus>

            <p class="error-message"><?= $errorProjectName ?></p>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" value="Добавить">
        </div>
    </form>
</div>
