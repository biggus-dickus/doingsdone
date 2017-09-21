<main class="content__main">
    <h2 class="content__main-heading">Регистрация аккаунта</h2>

    <form class="form" action="index.php" method="post">
        <input type="hidden" name="form_name" value="sign_up">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>

            <input class="form__input form__input--error" type="email" name="email" id="email" placeholder="Введите e-mail" autofocus>

            <p class="error-message">E-mail введён некорректно</p>
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>

            <input class="form__input" type="password" name="password" id="password" placeholder="Введите пароль">
        </div>

        <div class="form__row">
            <label class="form__label" for="name">Имя <sup>*</sup></label>

            <input class="form__input" type="text" name="name" id="name" placeholder="Введите имя">
        </div>

        <div class="form__row form__row--controls">
            <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>

            <input class="button" type="submit" value="Зарегистрироваться">
        </div>
    </form>
</main>
