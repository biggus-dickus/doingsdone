<?php
// Values
$email = $_POST['email'] ?? '';

// Error messages
$errorEmail = $data['errors']['email'] ?? '';
$errorPassword = $data['errors']['password'] ?? '';
?>


<div class="modal">
    <a href="?" class="modal__close" title="Закрыть">Закрыть</a>

    <h2 class="modal__heading">— Войдите!</h2>
    <?php if(isset($_GET['registration_success'])):?>
        <p class="form-message">Теперь вы&nbsp;можете войти, используя свой email и&nbsp;пароль.</p>
    <?php endif ?>

    <form class="form" action="index.php" method="post">
        <input type="hidden" name="form_name" value="sign_in">

        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>
            <input class="form__input <?php if($errorEmail):?>form__input--error<?php endif; ?>"
                   type="email"
                   name="email"
                   id="email"
                   maxlength="128"
                   placeholder="Введите e-mail"
                   value="<?=$email?>"
                   autofocus>
            <p class="error-message"><?=$errorEmail?></p>
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>
            <input class="form__input <?php if($errorPassword):?>form__input--error<?php endif; ?>"
                   type="password"
                   name="password"
                   id="password"
                   maxlength="32"
                   placeholder="Введите пароль">
            <p class="error-message"><?=$errorPassword?></p>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" value="Войти">
        </div>
    </form>
</div>
