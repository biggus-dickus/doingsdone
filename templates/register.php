<?php
// Values
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

// Error messages
$errorName = $data['errors']['name'] ?? '';
$errorEmail = $data['errors']['email'] ?? '';
$errorPassword = $data['errors']['password'] ?? '';
?>

<main class="content__main">
    <h2 class="content__main-heading">Регистрация аккаунта</h2>

    <form class="form" action="index.php" method="post">
        <input type="hidden" name="form_name" value="sign_up">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>

            <input class="form__input <?php if($errorEmail):?>form__input--error<?php endif; ?>"
                   type="email"
                   name="email"
                   id="email"
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
                   placeholder="Введите пароль">
            <p class="error-message"><?=$errorPassword?></p>
        </div>

        <div class="form__row">
            <label class="form__label" for="name">Имя <sup>*</sup></label>

            <input class="form__input <?php if($errorName):?>form__input--error<?php endif; ?>"
                   type="text"
                   name="name"
                   id="name"
                   value="<?=$name?>"
                   placeholder="Введите имя">
            <p class="error-message"><?=$errorName;?></p>
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" value="Зарегистрироваться">
        </div>
    </form>
</main>
