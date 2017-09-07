<div class="modal">
    <a href="?" class="modal__close" title="Закрыть">Закрыть</a>

    <h2 class="modal__heading">Вход для своих</h2>

    <form class="form" action="index.php" method="post">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>

            <input class="form__input" type="email" name="email" id="email" placeholder="Введите e-mail">

<!--            <p class="form__message">E-mail введён некорректно</p>-->
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>

            <input class="form__input" type="password" name="password" id="password" placeholder="Введите пароль">
        </div>

        <div class="form__row form__row--controls">
            <input class="button" type="submit" value="Войти">
        </div>
    </form>
</div>
