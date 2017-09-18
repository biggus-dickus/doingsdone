<?php
// Required fields
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Error messages
$errorEmail = $data['errors']['email'] ?? '';
$errorPassword = $data['errors']['password'] ?? '';
?>


<div class="modal">
    <a href="?" class="modal__close" title="Закрыть">Закрыть</a>

    <h2 class="modal__heading">— Войдите!</h2>

    <form class="form" action="../index.php" method="post">
        <input type="hidden" name="form_name" value="sign_in">

        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>
            <input class="form__input <?php if($errorEmail):?>form__input--error<?php endif; ?>" type="email" name="email" id="email" placeholder="Введите e-mail" value="<?=$email?>" autofocus>
            <p class="error-message"><?=$errorEmail?></p>
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>
            <input class="form__input <?php if($errorPassword):?>form__input--error<?php endif; ?>" type="password" name="password" id="password" placeholder="Введите пароль" value="<?=$password?>">
            <p class="error-message"><?=$errorPassword?></p>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" value="Войти">
        </div>
    </form>
</div>
