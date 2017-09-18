<?php

if (!mysqli_connect('localhost', 'root', 'root', 'doingsdone')) {
    print renderTemplate('templates/error.php', ['error' => mysqli_connect_error()]);
    exit;
}
