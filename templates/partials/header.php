<header class="main-header">
    <a>
        <img src="img/logo.png" width="153" height="42" alt="Логотип Дела в порядке">
    </a>

    <div class="main-header__side">
        <?php if(!isset($_SESSION['user'])): ?>
            <a class="main-header__side-item button button--transparent" href="?sign_in">Войти</a>
        <?php else: ?>
            <a class="main-header__side-item button button--plus" href="?add_task">Добавить задачу</a>
            <div class="main-header__side-item user-menu">
                <div class="user-menu__image">
                    <img src="img/user-pic.jpg" width="40" height="40" alt="Пользователь">
                </div>
                <div class="user-menu__data">
                    <p><?=strip_tags($_SESSION['user']['name']); ?></p>
                    <a href="?sign_out">Выйти</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>
